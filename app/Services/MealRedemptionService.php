<?php

namespace App\Services;

use App\Enums\EntitlementStatus;
use App\Enums\SubscriptionStatus;
use App\Models\MealEntitlement;
use App\Models\MealRedemption;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MealRedemptionService
{
    /**
     * Redeem today's meal for a subscription.
     *
     * @param string $accessCode
     * @param string $mealType
     * @return MealRedemption
     */
    public function redeem(
        string $accessCode,
        string $mealType
    ): MealRedemption {
        return DB::transaction(function () use (
            $accessCode,
            $mealType
        ) {
            $subscription = Subscription::where(
                'access_code',
                $accessCode
            )
                ->lockForUpdate()
                ->first();

            if (!$subscription) {
                throw new RuntimeException(
                    'Invalid subscription code.'
                );
            }

            if (
                $subscription->status !==
                SubscriptionStatus::ACTIVE
            ) {
                throw new RuntimeException(
                    'This subscription is not active.'
                );
            }

            $now = now();

            if (
                $now->lt($subscription->starts_at) ||
                $now->gt($subscription->ends_at)
            ) {
                throw new RuntimeException(
                    'This subscription is outside its valid period.'
                );
            }

            $today = $now->toDateString();

            $meal = $subscription->mealPlan
                ->meals()
                ->where('is_active', true)
                ->where(
                    'day_of_week',
                    $now->dayOfWeekIso
                )
                ->where(
                    'meal_type',
                    $mealType
                )
                ->first();

            if (!$meal) {
                throw new RuntimeException(
                    'No meal is configured for this meal period today.'
                );
            }

            $entitlement = MealEntitlement::where(
                'subscription_id',
                $subscription->id
            )
                ->where(
                    'meal_id',
                    $meal->id
                )
                ->where(
                    'scheduled_for',
                    $today
                )
                ->lockForUpdate()
                ->first();

            if (!$entitlement) {
                throw new RuntimeException(
                    'No meal entitlement exists for this meal today.'
                );
            }

            if (
                $entitlement->status !==
                EntitlementStatus::AVAILABLE
            ) {
                if (
                    $entitlement->status ===
                    EntitlementStatus::REDEEMED
                ) {
                    throw new RuntimeException(
                        'This meal has already been redeemed.'
                    );
                }

                throw new RuntimeException(
                    'This meal entitlement is no longer available.'
                );
            }

            if (
                $entitlement->expires_at &&
                $now->gt($entitlement->expires_at)
            ) {
                $entitlement->update([
                    'status' => EntitlementStatus::EXPIRED,
                ]);

                throw new RuntimeException(
                    'This meal entitlement has expired.'
                );
            }

            $entitlement->update([
                'status' => EntitlementStatus::REDEEMED,
            ]);

            return MealRedemption::create([
                'meal_entitlement_id' => $entitlement->id,
                'user_id' => $subscription->user_id,
                'meal_id' => $meal->id,
                'redeemed_at' => $now,
                'reference' => $this->generateReference(),
            ]);
        });
    }

    /**
     * Generate a unique redemption reference.
     */
    protected function generateReference(): string
    {
        do {
            $reference = 'SSR-' . strtoupper(
                Str::random(10)
            );
        } while (
            MealRedemption::where(
                'reference',
                $reference
            )->exists()
        );

        return $reference;
    }


    /**
 * Redeem a specific meal entitlement for its owner.
 */
    public function redeemEntitlement(
            MealEntitlement $entitlement,
            int $userId
        ): MealRedemption {
            return DB::transaction(function () use (
                $entitlement,
                $userId
            ) {
                $entitlement = MealEntitlement::with([
                    'subscription',
                    'meal',
                ])
                    ->whereKey($entitlement->id)
                    ->lockForUpdate()
                    ->first();

                if (!$entitlement) {
                    throw new RuntimeException(
                        'Meal entitlement not found.'
                    );
                }

                if (
                    $entitlement->subscription->user_id !== $userId
                ) {
                    throw new RuntimeException(
                        'You are not authorized to redeem this meal.'
                    );
                }

                if (
                    $entitlement->subscription->status !==
                    SubscriptionStatus::ACTIVE
                ) {
                    throw new RuntimeException(
                        'Your subscription is not active.'
                    );
                }

                if (
                    $entitlement->status !==
                    EntitlementStatus::AVAILABLE
                ) {
                    if (
                        $entitlement->status ===
                        EntitlementStatus::REDEEMED
                    ) {
                        throw new RuntimeException(
                            'This meal has already been redeemed.'
                        );
                    }

                    throw new RuntimeException(
                        'This meal entitlement is no longer available.'
                    );
                }

                $now = now();

                if (
                    $entitlement->expires_at &&
                    $now->gt($entitlement->expires_at)
                ) {
                    $entitlement->update([
                        'status' => EntitlementStatus::EXPIRED,
                    ]);

                    throw new RuntimeException(
                        'This meal entitlement has expired.'
                    );
                }

                $entitlement->update([
                    'status' => EntitlementStatus::REDEEMED,
                ]);

                return MealRedemption::create([
                    'meal_entitlement_id' => $entitlement->id,
                    'user_id' => $userId,
                    'meal_id' => $entitlement->meal_id,
                    'redeemed_at' => $now,
                    'reference' => $this->generateReference(),
                    'redeemed_by_user_id' => null,
                ]);
            });
        }


        /**
 * Staff serves a specific meal entitlement.
 */
    public function redeemForStaff(
        MealEntitlement $entitlement,
        int $staffUserId
    ): MealRedemption {
        return DB::transaction(function () use (
            $entitlement,
            $staffUserId
        ) {
            $entitlement = MealEntitlement::with([
                'subscription',
                'meal',
            ])
                ->whereKey($entitlement->id)
                ->lockForUpdate()
                ->first();

            if (!$entitlement) {
                throw new RuntimeException(
                    'Meal entitlement not found.'
                );
            }

            $subscription = $entitlement->subscription;

            if (!$subscription) {
                throw new RuntimeException(
                    'This meal has no valid subscription.'
                );
            }

            if (
                $subscription->status !==
                SubscriptionStatus::ACTIVE
            ) {
                throw new RuntimeException(
                    'This subscription is not active.'
                );
            }

            $now = now();

            if (
                $now->lt($subscription->starts_at) ||
                $now->gt($subscription->ends_at)
            ) {
                throw new RuntimeException(
                    'This subscription is outside its valid period.'
                );
            }

            /*
            * Staff may only serve today's entitlement.
            */
            if (
                !$entitlement->scheduled_for ||
                !$entitlement->scheduled_for->isToday()
            ) {
                throw new RuntimeException(
                    'This meal is not scheduled for today.'
                );
            }

            /*
            * Check expiration.
            */
            if (
                $entitlement->expires_at &&
                $now->gt($entitlement->expires_at)
            ) {
                $entitlement->update([
                    'status' => EntitlementStatus::EXPIRED,
                ]);

                throw new RuntimeException(
                    'This meal entitlement has expired.'
                );
            }

            /*
            * Prevent double serving.
            */
            if (
                $entitlement->status !==
                EntitlementStatus::AVAILABLE
            ) {
                if (
                    $entitlement->status ===
                    EntitlementStatus::REDEEMED
                ) {
                    throw new RuntimeException(
                        'This meal has already been served.'
                    );
                }

                throw new RuntimeException(
                    'This meal entitlement is no longer available.'
                );
            }

            /*
            * Mark the entitlement as redeemed.
            */
            $entitlement->update([
                'status' => EntitlementStatus::REDEEMED,
            ]);

            /*
            * Record exactly which staff member served it.
            */
            return MealRedemption::create([
                'meal_entitlement_id' => $entitlement->id,
                'user_id' => $subscription->user_id,
                'meal_id' => $entitlement->meal_id,
                'redeemed_at' => $now,
                'reference' => $this->generateReference(),
                'redeemed_by_user_id' => $staffUserId,
            ]);
        });
    }
}
