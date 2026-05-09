<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Gold extends BaseConfig
{
    /**
     * Prix de l'activation Gold en Ariary.
     */
    public int $priceAr = 120000;

    /**
     * Remise appliquée sur les régimes (0.15 = 15%).
     */
    public float $discountRate = 0.15;

    /**
     * Texte affiché dans l'UI.
     */
    public string $accessMode = 'Paiement unique, acces illimite aux remises.';
}
