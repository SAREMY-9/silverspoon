<?php

namespace App\Http\Controllers;

use App\Models\MealEntitlement;
use App\Services\MealRedemptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Throwable;

class MealRedemptionController extends Controller
{
    public function store(
        Request $request,
        MealEntitlement $mealEntitlement,
        MealRedemptionService $redemptionService
    ): JsonResponse {
        try {
            $redemption = $redemptionService->redeemEntitlement(
                $mealEntitlement,
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Meal redeemed successfully.',
                'reference' => $redemption->reference,
                'redeemed_at' => $redemption->redeemed_at
                    ?->toIso8601String(),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to redeem meal. Please try again.',
            ], 500);
        }
    }
}