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

    <title>Régime <?= esc($nom) ?></title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 6px;
        }

        h2 {
            font-size: 14px;
            margin: 18px 0 8px;
        }

        .meta {
            margin-bottom: 12px;
            color: #444;
            font-size: 11px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f5f5f5;
        }

        .small {
            font-size: 11px;
            color: #666;
        }

        .price {
            font-weight: bold;
        }

        .strike {
            text-decoration: line-through;
            color: #777;
            margin-right: 8px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            background: #eee;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <h1>Régime : <?= esc($nom) ?></h1>

    <p class="meta">
        Utilisateur : <?= esc($user['nom'] ?? '-') ?>
        <?php if (!empty($imc)): ?>
            — IMC : <?= esc((string) $imc) ?>
        <?php endif; ?>
    </p>

    <div class="box">
        <p><strong>Durée</strong> : <?= esc((string) $duree) ?> jours</p>

        <p><strong>Variation poids</strong> : <?= esc((string) $variation) ?> kg</p>

        <p>
            <strong>Composition</strong> :
            Viande <?= esc((string) $pourcentageViande) ?>% •
            Poisson <?= esc((string) $pourcentagePoisson) ?>% •
            Volaille <?= esc((string) $pourcentageVolaille) ?>%
        </p>

        <p>
            <strong>Prix</strong> :

            <?php if (!empty($isGold)): ?>
                <span class="strike">
                    <?= esc($formatNumber($prix)) ?> Ar
                </span>

                <span class="badge">
                    Gold <?= esc($discountLabel) ?>
                </span>
            <?php endif; ?>

            <span class="price">
                <?= esc($formatNumber($prixFinal ?? $prix)) ?> Ar
            </span>
        </p>
    </div>

    <h2>Activités recommandées</h2>

    <?php if (empty($activites)): ?>
        <p class="small">Aucune activité associée à ce régime.</p>
    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Durée</th>
                    <th>Description</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($activites as $a): ?>
                    <tr>
                        <td><?= esc($a['nom'] ?? '-') ?></td>
                        <td><?= esc((string) ($a['duree'] ?? 0)) ?> min</td>
                        <td><?= esc($a['description'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <h2>Recettes du régime</h2>

    <?php if (empty($recettes)): ?>
        <p class="small">Aucune recette associée à ce régime.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Repas</th>
                    <th>Recette</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recettes as $r): ?>
                    <tr>
                        <td><?= esc((string) ($r['jour'] ?? 0)) ?></td>
                        <td><?= esc($r['type_repas'] ?? '-') ?></td>
                        <td><?= esc($r['nom'] ?? '-') ?></td>
                        <td><?= esc($r['description'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>