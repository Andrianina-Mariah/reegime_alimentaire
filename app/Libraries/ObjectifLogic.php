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

    public static function objectifRecommande(?float $imc): string
    {
        $categorie = self::categorieImc($imc);

        return match ($categorie['key']) {
            'maigreur' => 'prise_poids',
            'surpoids', 'obesite' => 'perte_poids',
            'normal' => 'maintien',
            default => 'maintien',
        };
    }

    public static function objectifLabel(string $objectif): string
    {
        return match (strtolower(trim($objectif))) {
            'perte_poids' => 'Perte de poids',
            'prise_poids' => 'Prise de poids',
            default => 'Maintien',
        };
    }

    /**
     * @return array{title:string,details:string,categoryLabel:string}
     */
    public static function impact(string $objectif, ?float $imc): array
    {
        $categorie = self::categorieImc($imc);

        if ($categorie['key'] === 'inconnu') {
            return [
                'title' => 'Complète tes informations santé',
                'details' => "Renseigne taille et poids pour calculer l’IMC. L’objectif pourra ensuite ajuster les recommandations.",
                'categoryLabel' => $categorie['label'],
            ];
        }

        $objectif = strtolower(trim($objectif));

        $base = match ($objectif) {
            'perte_poids' => [
                'title' => 'Objectif : Perte de poids',
                'details' => "On privilégie une perte progressive et des activités cardio régulières.",
            ],
            'prise_poids' => [
                'title' => 'Objectif : Prise de poids',
                'details' => "On privilégie un surplus contrôlé et du renforcement musculaire.",
            ],
            default => [
                'title' => 'Objectif : Maintien',
                'details' => "On vise la stabilité : alimentation équilibrée et activité régulière.",
            ],
        };

        $extra = '';
        if ($objectif === 'perte_poids' && $categorie['key'] === 'maigreur') {
            $extra = " Avec un IMC bas, la perte de poids n’est généralement pas recommandée.";
        } elseif ($objectif === 'prise_poids' && ($categorie['key'] === 'surpoids' || $categorie['key'] === 'obesite')) {
            $extra = " Avec un IMC élevé, la prise de poids n’est généralement pas recommandée.";
        }

        return [
            'title' => $base['title'],
            'details' => $base['details'] . $extra,
            'categoryLabel' => $categorie['label'],
        ];
    }
}
