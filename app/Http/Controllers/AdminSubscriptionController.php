<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminSubscriptionController extends Controller
{
    /**
     * Display all subscriptions.
     */
    public function index(Request $request)
    {
        $query = Subscription::query()
            ->with([
                'user:id,name,email',
                'mealPlan:id,name,price',
            ])
            ->withCount([
                'payments',
                'entitlements',
            ])
            ->latest();

        /*
         * ---------------------------------------------------------
         * SEARCH
         * ---------------------------------------------------------
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('access_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mealPlan', function ($planQuery) use ($search) {
                        $planQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        /*
         * ---------------------------------------------------------
         * STATUS FILTER
         * ---------------------------------------------------------
         */
        if ($request->filled('status')) {
            $status = $request->status;

            $validStatuses = collect(
                SubscriptionStatus::cases()
            )->map(
                fn ($case) => $case->value
            );

            if ($validStatuses->contains($status)) {
                $query->where('status', $status);
            }
        }

        /*
         * ---------------------------------------------------------
         * MEAL PLAN FILTER
         * ---------------------------------------------------------
         */
        if ($request->filled('meal_plan_id')) {
            $query->where(
                'meal_plan_id',
                $request->meal_plan_id
            );
        }

        $subscriptions = $query
            ->paginate(25)
            ->withQueryString();

        /*
         * ---------------------------------------------------------
         * DASHBOARD COUNTS
         * ---------------------------------------------------------
         */
        $stats = [
            'total' => Subscription::count(),

            'pending' => Subscription::where(
                'status',
                SubscriptionStatus::PENDING
            )->count(),

            'active' => Subscription::where(
                'status',
                SubscriptionStatus::ACTIVE
            )->count(),

            'expired' => Subscription::where(
                'status',
                SubscriptionStatus::EXPIRED
            )->count(),

            'cancelled' => Subscription::where(
                'status',
                SubscriptionStatus::CANCELLED
            )->count(),
        ];

        /*
         * Meal plans available for filtering.
         */
        $mealPlans = \App\Models\MealPlan::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'admin.subscriptions.index',
            compact(
                'subscriptions',
                'stats',
                'mealPlans'
            )
        );
    }

    /**
     * Display a single subscription.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load([
            'user',
            'mealPlan',
            'payments' => function ($query) {
                $query->latest();
            },
            'entitlements' => function ($query) {
                $query->latest();
            },
        ]);

        return view(
            'admin.subscriptions.show',
            compact('subscription')
        );
    }

    /**
     * Cancel a subscription.
     *
     * We deliberately do not cancel pending subscriptions here
     * through payment logic. This is an administrative state change.
     */
    public function cancel(
        Request $request,
        Subscription $subscription
    ) {
        if (
            $subscription->status ===
            SubscriptionStatus::CANCELLED
        ) {
            return back()->with(
                'error',
                'This subscription is already cancelled.'
            );
        }

        if (
            $subscription->status ===
            SubscriptionStatus::EXPIRED
        ) {
            return back()->with(
                'error',
                'An expired subscription cannot be cancelled.'
            );
        }

        try {
            DB::transaction(function () use ($subscription) {

                $subscription->lockForUpdate();

                $subscription->update([
                    'status' =>
                        SubscriptionStatus::CANCELLED,
                ]);
            });

            Log::warning(
                'Subscription cancelled by administrator',
                [
                    'subscription_id' =>
                        $subscription->id,

                    'user_id' =>
                        $subscription->user_id,

                    'admin_id' =>
                        $request->user()->id,
                ]
            );

            return back()->with(
                'success',
                'Subscription cancelled successfully.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Admin subscription cancellation failed',
                [
                    'subscription_id' =>
                        $subscription->id,

                    'admin_id' =>
                        $request->user()->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'Unable to cancel the subscription.'
            );
        }
    }

    /**
     * Reactivate a cancelled subscription.
     *
     * IMPORTANT:
     *
     * We only allow this when the subscription still has a valid
     * paid period. We do NOT turn a cancelled subscription into
     * an active subscription without payment.
     */
    public function reactivate(
        Request $request,
        Subscription $subscription
    ) {
        if (
            $subscription->status !==
            SubscriptionStatus::CANCELLED
        ) {
            return back()->with(
                'error',
                'Only cancelled subscriptions can be reactivated.'
            );
        }

        /*
         * There must be a successful payment.
         */
        $successfulPayment = $subscription
            ->payments()
            ->where(
                'status',
                PaymentStatus::SUCCESSFUL
            )
            ->exists();

        if (!$successfulPayment) {
            return back()->with(
                'error',
                'This subscription has no successful payment and cannot be reactivated.'
            );
        }

        /*
         * The subscription must still have a valid period.
         */
        if (
            !$subscription->ends_at ||
            $subscription->ends_at->isPast()
        ) {
            return back()->with(
                'error',
                'This subscription period has expired. The customer must purchase a new subscription.'
            );
        }

        try {

            DB::transaction(function () use ($subscription) {

                $subscription->lockForUpdate();

                /*
                 * Do not allow two active subscriptions for one user.
                 */
                $existingActive = Subscription::query()
                    ->where('user_id', $subscription->user_id)
                    ->where(
                        'status',
                        SubscriptionStatus::ACTIVE
                    )
                    ->where('id', '!=', $subscription->id)
                    ->lockForUpdate()
                    ->exists();

                    
                if ($existingActive) {
                    throw new \RuntimeException(
                        'The customer already has another active subscription.'
                    );
                }

                $subscription->update([
                    'status' =>
                        SubscriptionStatus::ACTIVE,
                ]);
            });

            Log::warning(
                'Subscription reactivated by administrator',
                [
                    'subscription_id' =>
                        $subscription->id,

                    'user_id' =>
                        $subscription->user_id,

                    'admin_id' =>
                        $request->user()->id,
                ]
            );

            return back()->with(
                'success',
                'Subscription reactivated successfully.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Admin subscription reactivation failed',
                [
                    'subscription_id' =>
                        $subscription->id,

                    'admin_id' =>
                        $request->user()->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                $e->getMessage()
                    ?: 'Unable to reactivate the subscription.'
            );
        }
    }
}