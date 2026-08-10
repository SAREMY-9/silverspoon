<?php

namespace App\Http\Controllers;

use App\Enums\EntitlementStatus;
use App\Enums\SubscriptionStatus;
use App\Models\MealEntitlement;
use App\Models\MealRedemption;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminMealDashboardController extends Controller
{
    /**
     * Admin meal operations dashboard.
     */
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $today = now()->toDateString();

        /*
         * ---------------------------------------------------------
         * TODAY'S ENTITLEMENTS
         * ---------------------------------------------------------
         */

        $todayEntitlements = MealEntitlement::query()
            ->with('meal')
            ->whereDate('scheduled_for', $today)
            ->get();

        $totalExpected = $todayEntitlements->count();

        $totalServed = $todayEntitlements
            ->where('status', EntitlementStatus::REDEEMED)
            ->count();

        $totalRemaining = $todayEntitlements
            ->where('status', EntitlementStatus::AVAILABLE)
            ->count();

        $totalExpired = $todayEntitlements
            ->where('status', EntitlementStatus::EXPIRED)
            ->count();


        /*
         * ---------------------------------------------------------
         * MEAL TYPE BREAKDOWN
         * ---------------------------------------------------------
         */

        $mealTypes = [
            'breakfast',
            'lunch',
            'supper',
        ];

        $mealStats = [];

        foreach ($mealTypes as $type) {

            $entitlements = $todayEntitlements->filter(
                function ($entitlement) use ($type) {
                    return $entitlement->meal
                        && $entitlement->meal->meal_type === $type;
                }
            );

            $expected = $entitlements->count();

            $served = $entitlements
                ->where('status', EntitlementStatus::REDEEMED)
                ->count();

            $remaining = $entitlements
                ->where('status', EntitlementStatus::AVAILABLE)
                ->count();

            $expired = $entitlements
                ->where('status', EntitlementStatus::EXPIRED)
                ->count();

            $mealStats[$type] = [
                'expected' => $expected,
                'served' => $served,
                'remaining' => $remaining,
                'expired' => $expired,
            ];
        }


        /*
         * ---------------------------------------------------------
         * ACTIVE SUBSCRIPTIONS TODAY
         * ---------------------------------------------------------
         */

        $activeSubscriptions = Subscription::query()
            ->where(
                'status',
                SubscriptionStatus::ACTIVE
            )
            ->whereDate('starts_at', '<=', $today)
            ->whereDate('ends_at', '>=', $today)
            ->count();


        /*
         * ---------------------------------------------------------
         * CUSTOMERS WITH ACTIVE SUBSCRIPTIONS BUT
         * NO ENTITLEMENTS TODAY
         * ---------------------------------------------------------
         */

        $customersWithoutEntitlements = Subscription::query()
            ->with([
                'user',
                'mealPlan',
            ])
            ->where(
                'status',
                SubscriptionStatus::ACTIVE
            )
            ->whereDate('starts_at', '<=', $today)
            ->whereDate('ends_at', '>=', $today)
            ->whereDoesntHave('entitlements', function ($query) use ($today) {
                $query->whereDate('scheduled_for', $today);
            })
            ->get();


        /*
         * ---------------------------------------------------------
         * LATEST SERVICE ACTIVITY
         * ---------------------------------------------------------
         */

        $latestRedemptions = MealRedemption::query()
            ->from('meal_redemptions as mr')
            ->join(
                'users as customer',
                'customer.id',
                '=',
                'mr.user_id'
            )
            ->join(
                'meals',
                'meals.id',
                '=',
                'mr.meal_id'
            )
            ->leftJoin(
                'users as staff',
                'staff.id',
                '=',
                'mr.redeemed_by_user_id'
            )
            ->whereDate(
                'mr.redeemed_at',
                $today
            )
            ->select([
                'mr.id',
                'mr.reference',
                'mr.redeemed_at',

                'customer.name as customer_name',

                'meals.name as meal_name',
                'meals.meal_type',

                'staff.name as staff_name',
            ])
            ->orderByDesc('mr.redeemed_at')
            ->limit(10)
            ->get();


        /*
         * ---------------------------------------------------------
         * STAFF PERFORMANCE TODAY
         * ---------------------------------------------------------
         */

        $staffPerformance = MealRedemption::query()
            ->from('meal_redemptions as mr')
            ->leftJoin(
                'users as staff',
                'staff.id',
                '=',
                'mr.redeemed_by_user_id'
            )
            ->join(
                'meals',
                'meals.id',
                '=',
                'mr.meal_id'
            )
            ->whereDate(
                'mr.redeemed_at',
                $today
            )
            ->whereNotNull(
                'mr.redeemed_by_user_id'
            )
            ->select([
                'mr.redeemed_by_user_id',
                'staff.name as staff_name',

                DB::raw('COUNT(*) as meals_served'),

                DB::raw(
                    'MIN(mr.redeemed_at) as first_service'
                ),

                DB::raw(
                    'MAX(mr.redeemed_at) as last_service'
                ),
            ])
            ->groupBy(
                'mr.redeemed_by_user_id',
                'staff.name'
            )
            ->orderByDesc('meals_served')
            ->get();


        /*
         * ---------------------------------------------------------
         * TODAY'S SERVICE BY MEAL TYPE
         * ---------------------------------------------------------
         */

        $serviceByMealType = MealRedemption::query()
            ->join(
                'meals',
                'meals.id',
                '=',
                'meal_redemptions.meal_id'
            )
            ->whereDate(
                'meal_redemptions.redeemed_at',
                $today
            )
            ->select([
                'meals.meal_type',
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('meals.meal_type')
            ->get()
            ->keyBy('meal_type');


        /*
         * ---------------------------------------------------------
         * CUSTOMER SELF-REDEMPTIONS VS STAFF SERVICE
         * ---------------------------------------------------------
         */

        $staffServed = MealRedemption::query()
            ->whereDate('redeemed_at', $today)
            ->whereNotNull('redeemed_by_user_id')
            ->count();

        $customerSelfRedeemed = MealRedemption::query()
            ->whereDate('redeemed_at', $today)
            ->whereNull('redeemed_by_user_id')
            ->count();


        /*
         * ---------------------------------------------------------
         * RETURN DASHBOARD
         * ---------------------------------------------------------
         */

        return view('admin.meals.dashboard', [

            'today' => $today,

            'totalExpected' => $totalExpected,
            'totalServed' => $totalServed,
            'totalRemaining' => $totalRemaining,
            'totalExpired' => $totalExpired,

            'activeSubscriptions' => $activeSubscriptions,

            'mealStats' => $mealStats,

            'latestRedemptions' => $latestRedemptions,

            'staffPerformance' => $staffPerformance,

            'serviceByMealType' => $serviceByMealType,

            'staffServed' => $staffServed,
            'customerSelfRedeemed' => $customerSelfRedeemed,

            'customersWithoutEntitlements' =>
                $customersWithoutEntitlements,
        ]);
    }


    /**
     * Admin authorization.
     */
    protected function ensureAdmin(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user && $user->role === 'admin',
            403,
            'You are not authorized to access the meal dashboard.'
        );
    }
}