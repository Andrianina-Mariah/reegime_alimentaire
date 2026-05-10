<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creer un code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Creer un code</h1>
                <p>Genere un code et un montant pour le wallet utilisateur.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/codes" class="ghost-link btn btn-light btn-sm">Retour liste</a>
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
            <form class="admin-form" action="/admin/codes/creer" method="post">
                <div class="form-grid">
                    <label>
                        Code
                        <input type="text" name="code" value="<?= esc(old('code')) ?>" required>
                    </label>
                    <label>
                        Montant (Ar)
                        <input type="number" name="montant" min="0" step="0.01" value="<?= esc(old('montant')) ?>" required>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="primary-link">Enregistrer</button>
                    <a href="/admin/codes" class="ghost-link">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
