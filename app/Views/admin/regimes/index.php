<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regimes - Administration</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <main class="dashboard-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Gestion des regimes</h1>
                <p>Ajoute, modifie ou supprime les programmes disponibles.</p>
            </div>
            <div class="hero-actions">
                <a href="/admin/dashboard" class="ghost-link">Retour dashboard</a>
                <a href="/admin/regimes/creer" class="primary-link">Ajouter un regime</a>
            </div>
        </header>

        <?php if (session('success')): ?>
            <div class="alert alert-success">
                <p><?= esc(session('success')) ?></p>
            </div>
        <?php endif; ?>

        <?php if (session('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <section class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Duree (jours)</th>
                        <th>Prix</th>
                        <th>Variation</th>
                        <th>Viande %</th>
                        <th>Poisson %</th>
                        <th>Volaille %</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($regimes)): ?>
                        <tr>
                            <td colspan="8" class="empty-state">Aucun regime enregistre.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($regimes as $regime): ?>
                            <tr>
                                <td><?= esc($regime['nom'] ?? '-') ?></td>
                                <td><?= esc((string) ($regime['duree'] ?? 0)) ?></td>
                                <td><?= esc($formatNumber($regime['prix'] ?? 0)) ?> Ar</td>
                                <td><?= esc((string) ($regime['variation_poids'] ?? 0)) ?> kg</td>
                                <td><?= esc((string) ($regime['pourcentage_viande'] ?? 0)) ?>%</td>
                                <td><?= esc((string) ($regime['pourcentage_poisson'] ?? 0)) ?>%</td>
                                <td><?= esc((string) ($regime['pourcentage_volaille'] ?? 0)) ?>%</td>
                                <td class="col-actions">
                                    <a href="/admin/regimes/<?= esc((string) $regime['id']) ?>/modifier" class="ghost-link">Modifier</a>
                                    <form action="/admin/regimes/<?= esc((string) $regime['id']) ?>/supprimer" method="post" class="inline-form" onsubmit="return confirm('Supprimer ce regime ?');">
                                        <button type="submit" class="danger-link">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
