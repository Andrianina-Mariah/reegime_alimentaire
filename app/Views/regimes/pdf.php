<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Régime <?= esc((string) ($regime['id'] ?? '')) ?></title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 6px 0; }
        h2 { font-size: 14px; margin: 18px 0 8px 0; }
        .meta { margin: 0 0 12px 0; }
        .box { border: 1px solid #ddd; padding: 10px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f5f5f5; }
        .small { color: #444; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Régime : <?= esc((string) ($regime['nom'] ?? '-')) ?></h1>
    <p class="meta small">
        Généré le <?= esc(date('Y-m-d H:i')) ?>
        <?php if (!empty($user['nom'])): ?>
            — Utilisateur : <?= esc((string) $user['nom']) ?>
        <?php endif; ?>
    </p>

    <div class="box">
        <p><strong>Durée</strong> : <?= esc((string) ($regime['duree'] ?? '-')) ?> jours</p>
        <p>
            <strong>Prix</strong> :
            <?php if (!empty($isGold)): ?>
                <?= esc($formatNumber((float) ($regime['prix'] ?? 0))) ?> Ar
                <span class="small">(Gold <?= esc((string) ($goldDetails['discountLabel'] ?? '')) ?> → <?= esc($formatNumber((float) ($prixFinal ?? 0))) ?> Ar)</span>
            <?php else: ?>
                <?= esc($formatNumber((float) ($regime['prix'] ?? 0))) ?> Ar
            <?php endif; ?>
        </p>
        <?php if (!empty($objectifLabel) || !empty($categorieImcLabel)): ?>
            <p>
                <?php if (!empty($objectifLabel)): ?>
                    <strong>Objectif</strong> : <?= esc((string) $objectifLabel) ?>
                <?php endif; ?>
                <?php if (!empty($categorieImcLabel)): ?>
                    <br><strong>IMC</strong> : <?= esc((string) $categorieImcLabel) ?>
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </div>

    <h2>Activités recommandées</h2>
    <?php if (empty($activites)): ?>
        <p>Aucune activité recommandée pour ce régime.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Durée (min)</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activites as $a): ?>
                    <tr>
                        <td><?= esc((string) ($a['nom'] ?? '-')) ?></td>
                        <td><?= esc((string) ($a['duree'] ?? '-')) ?></td>
                        <td><?= esc((string) ($a['description'] ?? '-')) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
