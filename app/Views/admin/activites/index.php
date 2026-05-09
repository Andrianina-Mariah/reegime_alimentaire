<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activites - Administration</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <main class="dashboard-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Gestion des activites</h1>
                <p>Ajoute, modifie ou supprime les activites sportives.</p>
            </div>
            <div class="hero-actions">
                <a href="/admin/dashboard" class="ghost-link">Retour dashboard</a>
                <a href="/admin/activites/creer" class="primary-link">Ajouter une activite</a>
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
                        <th>Description</th>
                        <th>Duree (min)</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activites)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">Aucune activite enregistree.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activites as $activite): ?>
                            <tr>
                                <td><?= esc($activite['nom'] ?? '-') ?></td>
                                <td><?= esc($activite['description'] ?? '-') ?></td>
                                <td><?= esc((string) ($activite['duree'] ?? 0)) ?></td>
                                <td class="col-actions">
                                    <a href="/admin/activites/<?= esc((string) $activite['id']) ?>/modifier" class="ghost-link">Modifier</a>
                                    <form action="/admin/activites/<?= esc((string) $activite['id']) ?>/supprimer" method="post" class="inline-form" onsubmit="return confirm('Supprimer cette activite ?');">
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
