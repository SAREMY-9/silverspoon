<?php

namespace App\Enums;

enum EntitlementStatus: string
{
    case AVAILABLE = 'available';
    case REDEEMED = 'redeemed';
    case EXPIRED = 'expired';
}