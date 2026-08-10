<?php

namespace App\Models;

use App\Enums\EntitlementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\MealRedemption;


class MealEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'meal_id',
        'status',
        'expires_at',
        'scheduled_for',
    ];

    protected function casts(): array
    {
        return [
            'status' => EntitlementStatus::class,
            'expires_at' => 'datetime',
            'scheduled_for' => 'date',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function redemption(): HasOne
    {
        return $this->hasOne(MealRedemption::class);
    }
}