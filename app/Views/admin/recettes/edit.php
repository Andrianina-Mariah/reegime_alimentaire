<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une recette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Modifier une recette</h1>
                <p>Actualise les informations de la recette sélectionnée.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/recettes" class="quick-link btn btn-light btn-sm">Retour liste</a>
            </div>
        </header>

        <?php if (session('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <section class="form-card">
            <form class="admin-form" action="/admin/recettes/<?= esc((string) $recette['id']) ?>/modifier" method="post">
                <div class="form-grid">
                    <label>
                        Nom de la recette
                        <input type="text" name="nom" value="<?= esc(old('nom') ?? ($recette['nom'] ?? '')) ?>" required>
                    </label>
                    <label>
                        Type de repas (ex: petit-dejeuner)
                        <input type="text" name="type_repas" value="<?= esc(old('type_repas') ?? ($recette['type_repas'] ?? '')) ?>" required>
                    </label>
                    <label>
                        Description
                        <input type="text" name="description" value="<?= esc(old('description') ?? ($recette['description'] ?? '')) ?>">
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="primary-link">Enregistrer</button>
                    <a href="/admin/recettes" class="ghost-link">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
