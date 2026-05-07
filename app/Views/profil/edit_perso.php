<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier informations personnelles</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell profile-shell">
        <section class="auth-visual" aria-label="Presentation">
            <p class="eyebrow">Profil utilisateur</p>
            <h1>Met a jour tes informations personnelles.</h1>
            <p>
                Modifie ton nom, ton email ou ton genre. Ces informations
                servent a personnaliser ton experience.
            </p>

            <div class="progress-card">
                <span>Section</span>
                <strong>Infos perso</strong>
            </div>
        </section>

        <section class="auth-card">
            <a class="back-link" href="/profil">Retour au profil</a>
            <div class="card-heading">
                <span class="step-pill">P1</span>
                <h2>Modifier infos perso</h2>
                <p>Met a jour les donnees principales de ton compte.</p>
            </div>

            <?php if (session('errors')): ?>
                <div class="alert alert-error">
                    <?php foreach (session('errors') as $error): ?>
                        <p><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" action="/profil/modifier-infos-perso" method="post">
                <label for="nom">
                    Nom complet
                    <input type="text" id="nom" name="nom" value="<?= esc(old('nom') ?? ($user['nom'] ?? '')) ?>" required>
                </label>

                <label for="email">
                    Email
                    <input type="email" id="email" name="email" value="<?= esc(old('email') ?? ($user['email'] ?? '')) ?>" required>
                </label>

                <fieldset class="choice-group">
                    <legend>Genre</legend>
                    <?php $genre = old('genre') ?? ($user['genre'] ?? ''); ?>
                    <label class="choice-card">
                        <input type="radio" name="genre" value="femme" <?= $genre === 'femme' ? 'checked' : '' ?> required>
                        <span>Femme</span>
                    </label>
                    <label class="choice-card">
                        <input type="radio" name="genre" value="homme" <?= $genre === 'homme' ? 'checked' : '' ?>>
                        <span>Homme</span>
                    </label>
                </fieldset>

                <button type="submit" class="primary-button">Enregistrer les changements</button>
            </form>
        </section>
    </main>
</body>
</html>
