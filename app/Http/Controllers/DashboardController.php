<?php

    namespace App\Http\Controllers;

    use App\Enums\SubscriptionStatus;
    use App\Models\Subscription;
    use Illuminate\Support\Facades\Auth;

    class DashboardController extends Controller
    {
        public function index()
        {
            $user = Auth::user();

            /*
            * Active subscription
            */
            $activeSubscription = $user->subscriptions()
                ->where('status', SubscriptionStatus::ACTIVE)
                ->with([
                    'mealPlan',
                    'entitlements' => function ($query) {
                        $query->with([
                            'meal',
                            'redemption',
                        ])
                        ->orderBy('scheduled_for')
                        ->orderBy('meal_id');
                    },
                ])
                ->latest()
                ->first();

            /*
            * Today's meals
            */
            $today = now()->toDateString();

            $todayEntitlements = collect();

            if ($activeSubscription) {
                $todayEntitlements = $activeSubscription
                    ->entitlements()
                    ->with('meal')
                    ->whereDate('scheduled_for', $today)
                    ->get()
                    ->sortBy(function ($entitlement) {
                        return match ($entitlement->meal->meal_type ?? '') {
                            'breakfast' => 1,
                            'lunch' => 2,
                            'supper' => 3,
                            default => 4,
                        };
                    })
                    ->values();
            }
            /*
            * Upcoming meals
            */
            $upcomingEntitlements = collect();

            if ($activeSubscription) {
                $upcomingEntitlements = $activeSubscription
                    ->entitlements
                    ->filter(function ($entitlement) use ($today) {
                        return $entitlement->scheduled_for > $today;
                    })
                    ->take(9)
                    ->values();
            }

            /*
            * Days remaining
            */
            $daysRemaining = 0;

            if ($activeSubscription && $activeSubscription->ends_at) {
                $daysRemaining = max(
                    0,
                    now()->startOfDay()->diffInDays(
                        $activeSubscription->ends_at->startOfDay(),
                        false
                    )
                );
            }

            /*
            * Subscription history
            */
            $subscriptions = $user->subscriptions()
                ->with('mealPlan')
                ->latest()
                ->get();

            return view('dashboard', [
                'user' => $user,
                'activeSubscription' => $activeSubscription,
                'todayEntitlements' => $todayEntitlements,
                'upcomingEntitlements' => $upcomingEntitlements,
                'daysRemaining' => $daysRemaining,
                'subscriptions' => $subscriptions,
            ]);
        }
    }