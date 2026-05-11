<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 2</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <?= view('partials/top_nav'); ?>
    <main class="auth-shell">
        <section class="auth-visual auth-visual-alt" aria-label="Presentation">
            <p class="eyebrow">Profil sante</p>
            <h1>Ajoutons tes mesures de depart.</h1>
            <p>
                Ces donnees aideront ensuite l'application a relier ton compte
                utilisateur avec ton profil health.
            </p>

            <div class="progress-card">
                <span>Etape 2 sur 2</span>
                <strong>Taille et poids</strong>
                <div class="progress-track">
                    <div class="progress-fill"></div>
                </div>
            </div>
        </section>

        <section class="auth-card">
            <a class="back-link" href="/inscription">Retour a l'etape 1</a>
            <div class="card-heading">
                <span class="step-pill">02</span>
                <h2>Mesures</h2>
                <p>Complete ton profil avec ta taille et ton poids actuels.</p>
            </div>

            <?php if (session('errors')): ?>
                <div class="alert alert-error">
                    <?php foreach (session('errors') as $error): ?>
                        <p><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" action="/inscription/etape-2" method="post">
                <div class="field-grid">
                    <label for="taille">
                        Taille
                        <span class="unit-field">
                            <input type="number" id="taille" name="taille" value="<?= esc(old('taille')) ?>" placeholder="170" min="80" max="240" required>
                            <span>cm</span>
                        </span>
                    </label>

                    <label for="poids">
                        Poids
                        <span class="unit-field">
                            <input type="number" id="poids" name="poids" value="<?= esc(old('poids')) ?>" placeholder="65" min="20" max="300" step="0.1" required>
                            <span>kg</span>
                        </span>
                    </label>
                </div>

                <div class="summary-note">
                    <strong>A la validation :</strong>
                    sauvegarde dans <code>regime_utilisateurs</code> + <code>regime_sante</code>, avec calcul de l'IMC.
                </div>

                <button type="submit" class="primary-button">Finaliser l'inscription</button>
            </form>
        </section>
    </main>
</body>
</html>
