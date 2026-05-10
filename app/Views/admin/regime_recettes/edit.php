<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une association</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="dashboard-shell container-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Modifier une association</h1>
                <p>Actualise le régime, la recette et le jour.</p>
            </div>
            <div class="hero-actions d-flex flex-wrap gap-2">
                <a href="/admin/planning-recettes" class="quick-link btn btn-light btn-sm">Retour liste</a>
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
            <form class="admin-form" action="/admin/planning-recettes/<?= esc((string) $association['id']) ?>/modifier" method="post">
                <div class="form-grid">
                    <label>
                        Régime
                        <select name="regime_id" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach (($regimes ?? []) as $regime): ?>
                                <option value="<?= esc((string) $regime['id']) ?>" <?= (string) ($association['regime_id'] ?? '') === (string) ($regime['id'] ?? '') ? 'selected' : '' ?>>
                                    <?= esc($regime['nom'] ?? '-') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>
                        Recette
                        <select name="recette_id" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach (($recettes ?? []) as $recette): ?>
                                <option value="<?= esc((string) $recette['id']) ?>" <?= (string) ($association['recette_id'] ?? '') === (string) ($recette['id'] ?? '') ? 'selected' : '' ?>>
                                    <?= esc($recette['nom'] ?? '-') ?>
                                    (<?= esc($recette['type_repas'] ?? '-') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>
                        Jour (1-30)
                        <input type="number" name="jour" min="1" max="30" value="<?= esc((string) ($association['jour'] ?? 1)) ?>" required>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="primary-link">Enregistrer</button>
                    <a href="/admin/planning-recettes" class="ghost-link">Annuler</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
