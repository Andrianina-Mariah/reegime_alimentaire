<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };

    $status = (string) ($status ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codes - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Gestion des codes</h1>
                <p>Cree, supprime et suis l utilisation des codes wallet.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/dashboard" class="quick-link btn btn-light btn-sm">Retour dashboard</a>
                <a href="/admin/codes/creer" class="primary-link btn btn-success btn-sm">Creer un code</a>
            </div>
        </header>

        <section class="stats-grid" aria-label="Statistiques codes">
            <article class="stat-card">
                <span>Total</span>
                <strong><?= esc((string) ($stats['total'] ?? 0)) ?></strong>
                <p>Codes en base</p>
            </article>
            <article class="stat-card stat-card-warm">
                <span>Utilises</span>
                <strong><?= esc((string) ($stats['used'] ?? 0)) ?></strong>
                <p>Codes consommes</p>
            </article>
            <article class="stat-card stat-card-dark">
                <span>Disponibles</span>
                <strong><?= esc((string) ($stats['unused'] ?? 0)) ?></strong>
                <p>Codes non utilises</p>
            </article>
        </section>

        <section class="filter-row">
            <a href="/admin/codes" class="ghost-link<?= $status === '' ? ' is-active' : '' ?>">Tous</a>
            <a href="/admin/codes?status=unused" class="ghost-link<?= $status === 'unused' ? ' is-active' : '' ?>">Non utilises</a>
            <a href="/admin/codes?status=used" class="ghost-link<?= $status === 'used' ? ' is-active' : '' ?>">Utilises</a>
        </section>

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
                        <th>Code</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($codes)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">Aucun code a afficher.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($codes as $code): ?>
                            <?php $isUsed = (int) ($code['used'] ?? 0) === 1; ?>
                            <tr>
                                <td><?= esc($code['code'] ?? '-') ?></td>
                                <td><?= esc($formatNumber($code['montant'] ?? 0)) ?> Ar</td>
                                <td>
                                    <span class="badge <?= $isUsed ? 'badge-muted' : 'badge-success' ?>">
                                        <?= esc($isUsed ? 'Utilise' : 'Disponible') ?>
                                    </span>
                                </td>
                                <td class="col-actions">
                                    <form action="/admin/codes/<?= esc((string) $code['id']) ?>/supprimer" method="post" class="inline-form" onsubmit="return confirm('Supprimer ce code ?');">
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
