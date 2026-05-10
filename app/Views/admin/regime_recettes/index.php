<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning recettes - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Planning recettes</h1>
                <p>Associer des recettes à un régime et définir le jour.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/dashboard" class="quick-link btn btn-light btn-sm">Retour dashboard</a>
                <a href="/admin/planning-recettes/creer" class="primary-link btn btn-success btn-sm">Ajouter une association</a>
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
                        <th>Régime</th>
                        <th>Jour</th>
                        <th>Recette</th>
                        <th>Type</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($associations)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucune association enregistrée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($associations as $assoc): ?>
                            <tr>
                                <td><?= esc($assoc['regime_nom'] ?? '-') ?></td>
                                <td><?= esc((string) ($assoc['jour'] ?? 0)) ?></td>
                                <td><?= esc($assoc['recette_nom'] ?? '-') ?></td>
                                <td><?= esc($assoc['type_repas'] ?? '-') ?></td>
                                <td class="col-actions">
                                    <a href="/admin/planning-recettes/<?= esc((string) $assoc['id']) ?>/modifier" class="ghost-link">Modifier</a>
                                    <form action="/admin/planning-recettes/<?= esc((string) $assoc['id']) ?>/supprimer" method="post" class="inline-form" onsubmit="return confirm('Supprimer cette association ?');">
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
