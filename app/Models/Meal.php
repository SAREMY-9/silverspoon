<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    use HasFactory;

        protected $fillable = [
        'meal_plan_id',
        'name',
        'description',
        'image',
        'price',
        'meal_type',
        'day_of_week',
        'is_active',
    ];

    protected function casts(): array
        {
            return [
                'price' => 'decimal:2',
                'day_of_week' => 'integer',
                'is_active' => 'boolean',
            ];
        }

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function entitlements(): HasMany
    {
        return $this->hasMany(MealEntitlement::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(MealRedemption::class);
    }


    public function subscriptionSelections(): HasMany
    {
        return $this->hasMany(
            SubscriptionMealSelection::class
        );
    }

}