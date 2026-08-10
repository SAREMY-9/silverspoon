<?php

namespace App\Http\Controllers;

use App\Enums\EntitlementStatus;
use App\Enums\SubscriptionStatus;
use App\Models\MealEntitlement;
use App\Models\Subscription;
use App\Services\MealRedemptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class StaffMealController extends Controller
{
    /**
     * Staff QR scanner.
     */
    public function scanner(Request $request): View
    {
        $this->ensureStaff($request);

        return view('staff.meals.scan');
    }

    /**
     * Validate a customer's QR token.
     *
     * Does NOT redeem anything.
     */
    public function validateQr(
        Request $request
    ): JsonResponse {
        $this->ensureStaff($request);

        $request->validate([
            'token' => [
                'required',
                'string',
            ],
        ]);

        $subscription = Subscription::with([
            'user',
            'mealPlan',
            'entitlements.meal',
        ])
            ->where('qr_token', $request->token)
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
            ], 404);
        }

        $now = now();

        if ($subscription->status !== SubscriptionStatus::ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'This subscription is not active.',
            ], 422);
        }

        if (
            $now->lt($subscription->starts_at) ||
            $now->gt($subscription->ends_at)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This subscription is outside its valid period.',
            ], 422);
        }

        $today = $now->toDateString();

        $todayEntitlements = $subscription
            ->entitlements
            ->filter(function ($entitlement) use ($today) {
                return $entitlement->scheduled_for?->toDateString() === $today;
            })
            ->sortBy(function ($entitlement) {
                return match (
                    $entitlement->meal->meal_type ?? ''
                ) {
                    'breakfast' => 1,
                    'lunch' => 2,
                    'supper' => 3,
                    default => 4,
                };
            })
            ->values();

        return response()->json([
            'success' => true,

            'customer' => [
                'id' => $subscription->user->id,
                'name' => $subscription->user->name,
            ],

            'subscription' => [
                'id' => $subscription->id,
                'plan' => $subscription->mealPlan->name,
                'status' => $subscription->status->value,
                'starts_at' => $subscription->starts_at?->toIso8601String(),
                'ends_at' => $subscription->ends_at?->toIso8601String(),
            ],

            'meals' => $todayEntitlements->map(function ($entitlement) {
                return [
                    'id' => $entitlement->id,
                    'meal_id' => $entitlement->meal_id,
                    'name' => $entitlement->meal->name,
                    'type' => $entitlement->meal->meal_type,
                    'status' => $entitlement->status->value,
                    'expires_at' => $entitlement->expires_at?->toIso8601String(),
                ];
            })->values(),
        ]);
    }

    /**
     * Serve/redeem a specific entitlement.
     */
    public function serve(
        Request $request,
        MealEntitlement $mealEntitlement,
        MealRedemptionService $redemptionService
    ): JsonResponse {
        $this->ensureStaff($request);

        try {
            $redemption = $redemptionService->redeemForStaff(
                $mealEntitlement,
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Meal served successfully.',
                'reference' => $redemption->reference,
                'redeemed_at' => $redemption->redeemed_at
                    ?->toIso8601String(),
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to serve meal. Please try again.',
            ], 500);
        }
    }

    /**
     * Basic staff authorization.
     */
    protected function ensureStaff(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user && in_array($user->role, ['staff', 'admin'], true),
            403,
            'You are not authorized to access the staff meal scanner.'
        );
    }




    /**
 * Today's meal service summary.
 */
    public function summary(Request $request): JsonResponse
    {
            $this->ensureStaff($request);

            $today = now()->toDateString();

            $total = MealEntitlement::whereDate('scheduled_for', $today)
                ->count();

            $served = MealEntitlement::whereDate('scheduled_for', $today)
                ->where('status', EntitlementStatus::REDEEMED)
                ->count();

            $available = MealEntitlement::whereDate('scheduled_for', $today)
                ->where('status', EntitlementStatus::AVAILABLE)
                ->count();

            $expired = MealEntitlement::whereDate('scheduled_for', $today)
                ->where('status', EntitlementStatus::EXPIRED)
                ->count();

            $byType = MealEntitlement::with('meal')
                ->whereDate('scheduled_for', $today)
                ->get()
                ->groupBy(function ($entitlement) {
                    return $entitlement->meal->meal_type ?? 'other';
                })
                ->map(function ($entitlements) {
                    return [
                        'total' => $entitlements->count(),

                        'served' => $entitlements
                            ->where(
                                'status',
                                EntitlementStatus::REDEEMED
                            )
                            ->count(),

                        'available' => $entitlements
                            ->where(
                                'status',
                                EntitlementStatus::AVAILABLE
                            )
                            ->count(),

                        'expired' => $entitlements
                            ->where(
                                'status',
                                EntitlementStatus::EXPIRED
                            )
                            ->count(),
                    ];
                });

            return response()->json([
                'success' => true,

                'total' => $total,
                'served' => $served,
                'available' => $available,
                'expired' => $expired,

                'by_type' => $byType,
            ]);
        }
}