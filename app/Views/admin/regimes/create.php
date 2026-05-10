<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un regime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Ajouter un regime</h1>
                <p>Definis le programme, son prix et ses pourcentages alimentaires.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/regimes" class="ghost-link btn btn-light btn-sm">Retour liste</a>
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
            <form class="admin-form" action="/admin/regimes/creer" method="post">
                <div class="form-grid">
                    <label>
                        Nom du regime
                        <input type="text" name="nom" value="<?= esc(old('nom')) ?>" required>
                    </label>
                    <label>
                        Duree (jours)
                        <input type="number" name="duree" min="1" value="<?= esc(old('duree')) ?>" required>
                    </label>
                    <label>
                        Prix (Ar)
                        <input type="number" name="prix" min="0" step="0.01" value="<?= esc(old('prix')) ?>" required>
                    </label>
                    <label>
                        Variation poids (kg)
                        <input type="number" name="variation_poids" step="0.1" value="<?= esc(old('variation_poids')) ?>" required>
                    </label>
                    <label>
                        % Viande
                        <input type="number" name="pourcentage_viande" min="0" max="100" value="<?= esc(old('pourcentage_viande')) ?>" required>
                    </label>
                    <label>
                        % Poisson
                        <input type="number" name="pourcentage_poisson" min="0" max="100" value="<?= esc(old('pourcentage_poisson')) ?>" required>
                    </label>
                    <label>
                        % Volaille
                        <input type="number" name="pourcentage_volaille" min="0" max="100" value="<?= esc(old('pourcentage_volaille')) ?>" required>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="primary-link">Enregistrer</button>
                    <a href="/admin/regimes" class="ghost-link">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
