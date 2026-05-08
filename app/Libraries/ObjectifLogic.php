<?php

namespace App\Libraries;

class ObjectifLogic
{
    /**
     * @return array{key:string,label:string}
     */
    public static function categorieImc(?float $imc): array
    {
        if ($imc === null || $imc <= 0) {
            return [
                'key' => 'inconnu',
                'label' => 'IMC non disponible',
            ];
        }

        if ($imc < 18.5) {
            return [
                'key' => 'maigreur',
                'label' => 'Maigreur (< 18.5)',
            ];
        }

        if ($imc < 25) {
            return [
                'key' => 'normal',
                'label' => 'Corpulence normale (18.5–24.9)',
            ];
        }

        if ($imc < 30) {
            return [
                'key' => 'surpoids',
                'label' => 'Surpoids (25–29.9)',
            ];
        }

        return [
            'key' => 'obesite',
            'label' => 'Obésité (≥ 30)',
        ];
    }

    /**
     * @return array{title:string,details:string,categoryLabel:string}
     */
    public static function impact(string $objectif, ?float $imc): array
    {
        $category = self::categorieImc($imc);
        $categoryLabel = $category['label'];

        if ($category['key'] === 'inconnu') {
            return [
                'title' => 'Complete tes infos santé',
                'details' => "Renseigne taille et poids pour calculer l’IMC. L’objectif pourra ensuite ajuster les recommandations.",
                'categoryLabel' => $categoryLabel,
            ];
        }

        $objectif = strtolower(trim($objectif));

        $base = match ($objectif) {
            'perte_poids' => [
                'title' => 'Objectif : Perte de poids',
                'details' => "On privilégie un déficit calorique progressif et des activités cardio/quotidiennes.",
            ],
            'maintien' => [
                'title' => 'Objectif : Maintien',
                'details' => "On vise la stabilité : équilibre alimentaire et activité régulière.",
            ],
            'prise_poids' => [
                'title' => 'Objectif : Prise de poids',
                'details' => "On vise un surplus calorique contrôlé et du renforcement musculaire.",
            ],
            default => [
                'title' => 'Objectif : Non défini',
                'details' => "Choisis un objectif pour personnaliser l’expérience.",
            ],
        };

        $extra = '';
        if ($objectif === 'perte_poids' && $category['key'] === 'maigreur') {
            $extra = " Avec un IMC bas, la perte de poids n’est généralement pas recommandée : privilégie plutôt le maintien (ou demande un avis médical).";
        } elseif ($objectif === 'prise_poids' && ($category['key'] === 'surpoids' || $category['key'] === 'obesite')) {
            $extra = " Avec un IMC déjà élevé, la prise de poids n’est généralement pas recommandée : privilégie plutôt le maintien.";
        } elseif ($objectif === 'perte_poids' && ($category['key'] === 'surpoids' || $category['key'] === 'obesite')) {
            $extra = " Ton IMC suggère qu’un objectif de perte de poids est cohérent : vise surtout la régularité.";
        }

        return [
            'title' => $base['title'],
            'details' => $base['details'] . $extra,
            'categoryLabel' => $categoryLabel,
        ];
    }
}
