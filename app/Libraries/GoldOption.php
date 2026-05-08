<?php

namespace App\Libraries;

class GoldOption
{
    public const PRICE_AR = 120000;
    public const DISCOUNT_RATE = 0.15;
    public const ACCESS_MODE = 'Paiement unique, acces illimite aux remises.';

    /**
     * @return array{price:int,discountRate:float,discountLabel:string,accessMode:string}
     */
    public static function details(): array
    {
        return [
            'price' => self::PRICE_AR,
            'discountRate' => self::DISCOUNT_RATE,
            'discountLabel' => sprintf('-%d%%', (int) round(self::DISCOUNT_RATE * 100)),
            'accessMode' => self::ACCESS_MODE,
        ];
    }

    public static function applyDiscount(float $prix): float
    {
        return $prix * (1 - self::DISCOUNT_RATE);
    }
}
