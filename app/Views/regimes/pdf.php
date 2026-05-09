<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };
    $nom = (string) ($regime['nom'] ?? '-');
    $duree = (int) ($regime['duree'] ?? 0);
    $variation = (float) ($regime['variation_poids'] ?? 0);
    $pourcentageViande = (int) ($regime['pourcentage_viande'] ?? 0);
    $pourcentagePoisson = (int) ($regime['pourcentage_poisson'] ?? 0);
    $pourcentageVolaille = (int) ($regime['pourcentage_volaille'] ?? 0);
    $prix = (float) ($regime['prix'] ?? 0);
    $discountLabel = (string) ($goldDetails['discountLabel'] ?? '-15%');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Regime - <?= esc($nom) ?></title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #18201b;
            font-size: 12px;
        }
        h1, h2 {
            margin: 0 0 10px;
        }
        .muted {
            color: #69746c;
        }
        .section {
            margin: 18px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            background: #f0f3f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid #e4e6e3;
        }
        th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #69746c;
        }
        .price {
            font-size: 16px;
            font-weight: bold;
        }
        .strike {
            text-decoration: line-through;
            color: #69746c;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <h1>Regime: <?= esc($nom) ?></h1>
    <p class="muted">Utilisateur: <?= esc($user['nom'] ?? '-') ?> | IMC: <?= esc(isset($imc) && $imc !== null ? (string) $imc : '-') ?></p>

    <div class="section">
        <h2>Details du regime</h2>
        <table>
            <tr>
                <th>Duree</th>
                <td><?= esc((string) $duree) ?> jours</td>
            </tr>
            <tr>
                <th>Variation</th>
                <td><?= esc((string) $variation) ?> kg</td>
            </tr>
            <tr>
                <th>Composition</th>
                <td>Viande <?= esc((string) $pourcentageViande) ?>% • Poisson <?= esc((string) $pourcentagePoisson) ?>% • Volaille <?= esc((string) $pourcentageVolaille) ?>%</td>
            </tr>
            <tr>
                <th>Prix</th>
                <td>
                    <?php if (! empty($isGold)): ?>
                        <span class="strike"><?= esc($formatNumber($prix)) ?> Ar</span>
                        <span class="badge">Gold <?= esc($discountLabel) ?></span>
                    <?php endif; ?>
                    <span class="price"><?= esc($formatNumber($prixFinal)) ?> Ar</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Activites recommandees</h2>
        <?php if (empty($activites)): ?>
            <p class="muted">Aucune activite associee a ce regime.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Activite</th>
                        <th>Duree</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activites as $activite): ?>
                        <tr>
                            <td><?= esc($activite['nom'] ?? '-') ?></td>
                            <td><?= esc((string) ($activite['duree'] ?? 0)) ?> min</td>
                            <td><?= esc($activite['description'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
