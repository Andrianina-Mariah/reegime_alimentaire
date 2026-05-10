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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Gestion des regimes</h1>
                <p>Ajoute, modifie ou supprime les programmes disponibles.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/dashboard" class="quick-link btn btn-light btn-sm">Retour dashboard</a>
                <a href="/admin/regimes/creer" class="primary-link btn btn-success btn-sm">Ajouter un regime</a>
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
                        <th>Achats</th>
                        <th>Total encaissé</th>
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
                            <td colspan="10" class="empty-state">Aucun regime enregistre.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($regimes as $regime): ?>
                            <tr>
                                <td><?= esc($regime['nom'] ?? '-') ?></td>
                                <td><?= esc((string) ($regime['duree'] ?? 0)) ?></td>
                                <td><?= esc($formatNumber($regime['prix'] ?? 0)) ?> Ar</td>
                                <td><?= esc((string) ($regime['achats_count'] ?? 0)) ?></td>
                                <td><?= esc($formatNumber($regime['total_revenue'] ?? 0)) ?> Ar</td>
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
