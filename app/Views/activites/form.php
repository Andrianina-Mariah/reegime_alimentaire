<?php
    $mode = (string) ($mode ?? 'create');
    $isEdit = $mode === 'edit';

    $nom = old('nom', (string) ($activite['nom'] ?? ''));
    $duree = old('duree', (string) ($activite['duree'] ?? ''));
    $description = old('description', (string) ($activite['description'] ?? ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activités - <?= $isEdit ? 'Modifier' : 'Ajouter' ?></title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <?= view('partials/top_nav'); ?>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Activités">
            <p class="eyebrow">Activités</p>
            <h1><?= $isEdit ? 'Modifier' : 'Ajouter' ?> une activité</h1>
            <p>Renseigne le nom, la durée et une description optionnelle.</p>

            <div class="progress-card">
                <span>Action</span>
                <strong><?= $isEdit ? 'Edition' : 'Création' ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/activites">Retour liste</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">AC</span>
                <h2><?= $isEdit ? 'Modifier' : 'Ajouter' ?> une activité</h2>
                <p>La durée est en minutes.</p>
            </div>

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

            <form class="auth-form" action="<?= esc((string) ($action ?? '/activites')) ?>" method="post">
                <label for="nom">
                    Nom
                    <input type="text" id="nom" name="nom" value="<?= esc($nom) ?>" placeholder="Course à pied" required>
                </label>

                <label for="duree">
                    Durée (minutes)
                    <input type="number" id="duree" name="duree" value="<?= esc($duree) ?>" min="1" max="600" required>
                </label>

                <label for="description">
                    Description (optionnel)
                    <textarea id="description" name="description" rows="4" placeholder="Ex: 30 minutes à rythme modéré."><?= esc($description) ?></textarea>
                </label>

                <button type="submit" class="primary-button"><?= $isEdit ? 'Enregistrer' : 'Ajouter' ?></button>
            </form>
        </section>
    </main>
</body>
</html>

