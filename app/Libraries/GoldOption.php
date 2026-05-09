<?php

namespace App\Libraries;

class GoldOption
{
    public const PRICE_AR = 120000;
    public const DISCOUNT_RATE = 0.15;
    public const ACCESS_MODE = 'Paiement unique, acces illimite aux remises.';

    private static function config(): ?\Config\Gold
    {
        try {
            /** @var \Config\Gold $cfg */
            $cfg = config('Gold');
            return $cfg;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @return array{price:int,discountRate:float,discountLabel:string,accessMode:string}
     */
    public static function details(): array
    {
        $cfg = self::config();
        $price = $cfg?->priceAr ?? self::PRICE_AR;
        $discountRate = $cfg?->discountRate ?? self::DISCOUNT_RATE;
        $accessMode = $cfg?->accessMode ?? self::ACCESS_MODE;

        return [
            'price' => $price,
            'discountRate' => $discountRate,
            'discountLabel' => sprintf('-%d%%', (int) round($discountRate * 100)),
            'accessMode' => $accessMode,
        ];
    }

    public static function applyDiscount(float $prix): float
    {
        $cfg = self::config();
        $discountRate = $cfg?->discountRate ?? self::DISCOUNT_RATE;
        return $prix * (1 - $discountRate);
    }
}
