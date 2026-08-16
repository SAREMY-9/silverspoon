<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionMealSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'meal_id',
        'day_of_week',
        'meal_type',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'unit_price' => 'decimal:2',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(
            Subscription::class
        );
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(
            Meal::class
        );
    }
}