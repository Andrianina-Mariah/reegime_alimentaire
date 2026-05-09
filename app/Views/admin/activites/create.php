<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une activite</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <main class="dashboard-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Ajouter une activite</h1>
                <p>Definis l activite, sa description et sa duree.</p>
            </div>
            <div class="hero-actions">
                <a href="/admin/activites" class="ghost-link">Retour liste</a>
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
            <form class="admin-form" action="/admin/activites/creer" method="post">
                <div class="form-grid">
                    <label>
                        Nom de l activite
                        <input type="text" name="nom" value="<?= esc(old('nom')) ?>" required>
                    </label>
                    <label>
                        Duree (min)
                        <input type="number" name="duree" min="1" value="<?= esc(old('duree')) ?>" required>
                    </label>
                    <label>
                        Description
                        <input type="text" name="description" value="<?= esc(old('description')) ?>">
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="primary-link">Enregistrer</button>
                    <a href="/admin/activites" class="ghost-link">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
