<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_plan_id',
        'starts_at',
        'ends_at',
        'status',
        'access_code',
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => SubscriptionStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function entitlements(): HasMany
    {
        return $this->hasMany(MealEntitlement::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}