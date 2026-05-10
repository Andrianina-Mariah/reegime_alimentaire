<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Gestion des recettes</h1>
                <p>Ajoute, modifie ou supprime les recettes proposées.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/dashboard" class="quick-link btn btn-light btn-sm">Retour dashboard</a>
                <a href="/admin/recettes/creer" class="primary-link btn btn-success btn-sm">Ajouter une recette</a>
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
                        <th>Type</th>
                        <th>Description</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recettes)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">Aucune recette enregistrée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recettes as $recette): ?>
                            <tr>
                                <td><?= esc($recette['nom'] ?? '-') ?></td>
                                <td><?= esc($recette['type_repas'] ?? '-') ?></td>
                                <td><?= esc($recette['description'] ?? '-') ?></td>
                                <td class="col-actions">
                                    <a href="/admin/recettes/<?= esc((string) $recette['id']) ?>/modifier" class="ghost-link">Modifier</a>
                                    <form action="/admin/recettes/<?= esc((string) $recette['id']) ?>/supprimer" method="post" class="inline-form" onsubmit="return confirm('Supprimer cette recette ?');">
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
