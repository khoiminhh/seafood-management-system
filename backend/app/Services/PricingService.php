<?php

namespace App\Services;

class PricingService
{
    public static function calculatePrice($basePrice, $variantAdjustment = 0, $processingFees = 0)
    {
        return $basePrice + $variantAdjustment + $processingFees;
    }
}
