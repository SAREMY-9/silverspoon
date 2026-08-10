<?php

namespace App\Http\Controllers;

use App\Models\MealRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminMealReportController extends Controller
{
    /**
     * Meal service report dashboard.
     */
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $date = $request->input(
            'date',
            now()->toDateString()
        );

        $staffId = $request->input('staff_id');

        $customer = $request->input('customer');

        /*
         * Base redemption query.
         */
        $query = MealRedemption::query()
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
                $date
            );

        /*
         * Optional staff filter.
         */
        if ($staffId) {
            $query->where(
                'mr.redeemed_by_user_id',
                $staffId
            );
        }

        /*
         * Optional customer search.
         */
        if ($customer) {
            $query->where(function ($q) use ($customer) {
                $q->where(
                    'customer.name',
                    'like',
                    "%{$customer}%"
                )
                ->orWhere(
                    'customer.email',
                    'like',
                    "%{$customer}%"
                );
            });
        }

        $redemptions = (clone $query)
            ->select([
                'mr.id',
                'mr.reference',
                'mr.redeemed_at',

                'customer.id as customer_id',
                'customer.name as customer_name',
                'customer.email as customer_email',

                'meals.id as meal_id',
                'meals.name as meal_name',
                'meals.meal_type',

                'staff.id as staff_id',
                'staff.name as staff_name',
            ])
            ->orderByDesc('mr.redeemed_at')
            ->paginate(30)
            ->withQueryString();

        /*
         * Summary totals.
         */
        $totalServed = (clone $query)->count();

        $breakfast = (clone $query)
            ->where('meals.meal_type', 'breakfast')
            ->count();

        $lunch = (clone $query)
            ->where('meals.meal_type', 'lunch')
            ->count();

        $supper = (clone $query)
            ->where('meals.meal_type', 'supper')
            ->count();

        /*
         * Staff performance.
         */
        $staffSummary = (clone $query)
            ->select([
                'mr.redeemed_by_user_id',
                DB::raw(
                    'COALESCE(staff.name, "Customer") as staff_name'
                ),
                DB::raw('COUNT(*) as meals_served'),
            ])
            ->groupBy(
                'mr.redeemed_by_user_id',
                'staff.name'
            )
            ->orderByDesc('meals_served')
            ->get();

        /*
         * Meal breakdown.
         */
        $mealSummary = (clone $query)
            ->select([
                'meals.meal_type',
                DB::raw('COUNT(*) as meals_served'),
            ])
            ->groupBy('meals.meal_type')
            ->orderByRaw("
                CASE meals.meal_type
                    WHEN 'breakfast' THEN 1
                    WHEN 'lunch' THEN 2
                    WHEN 'supper' THEN 3
                    ELSE 4
                END
            ")
            ->get();

        /*
         * Staff list for filter.
         */
        $staff = DB::table('users')
            ->whereIn('role', ['staff', 'admin'])
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'role',
            ]);

        return view('admin.meals.report', [
            'date' => $date,
            'staffId' => $staffId,
            'customer' => $customer,

            'redemptions' => $redemptions,

            'totalServed' => $totalServed,
            'breakfast' => $breakfast,
            'lunch' => $lunch,
            'supper' => $supper,

            'staffSummary' => $staffSummary,
            'mealSummary' => $mealSummary,

            'staff' => $staff,
        ]);
    }

    /**
     * Export meal service report as CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $this->ensureAdmin($request);

        $date = $request->input(
            'date',
            now()->toDateString()
        );

        $staffId = $request->input('staff_id');

        $customer = $request->input('customer');

        $query = MealRedemption::query()
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
                $date
            );

        if ($staffId) {
            $query->where(
                'mr.redeemed_by_user_id',
                $staffId
            );
        }

        if ($customer) {
            $query->where(function ($q) use ($customer) {
                $q->where(
                    'customer.name',
                    'like',
                    "%{$customer}%"
                )
                ->orWhere(
                    'customer.email',
                    'like',
                    "%{$customer}%"
                );
            });
        }

        $rows = $query
            ->select([
                'mr.reference',
                'mr.redeemed_at',

                'customer.name as customer_name',
                'customer.email as customer_email',

                'meals.name as meal_name',
                'meals.meal_type',

                'staff.name as staff_name',
            ])
            ->orderBy('mr.redeemed_at')
            ->cursor();

        $filename = "meal-service-{$date}.csv";

        return response()->streamDownload(function () use ($rows) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Reference',
                'Served At',
                'Customer',
                'Customer Email',
                'Meal',
                'Meal Type',
                'Served By',
            ]);

            foreach ($rows as $row) {

                fputcsv($handle, [
                    $row->reference,
                    $row->redeemed_at,
                    $row->customer_name,
                    $row->customer_email,
                    $row->meal_name,
                    ucfirst($row->meal_type),
                    $row->staff_name ?? 'Customer',
                ]);
            }

            fclose($handle);

        }, $filename);
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
            'You are not authorized to access meal reports.'
        );
    }
}