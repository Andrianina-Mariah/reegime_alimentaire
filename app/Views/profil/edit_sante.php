<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier informations sante</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell profile-shell">
        <section class="auth-visual auth-visual-alt" aria-label="Presentation">
            <p class="eyebrow">Profil sante</p>
            <h1>Actualise tes mesures de sante.</h1>
            <p>
                Mets a jour ta taille et ton poids pour recalculer ton IMC
                et recevoir des recommandations plus justes.
            </p>

            <div class="progress-card">
                <span>Section</span>
                <strong>Infos sante</strong>
            </div>
        </section>

        <section class="auth-card">
            <a class="back-link" href="/profil">Retour au profil</a>
            <div class="card-heading">
                <span class="step-pill">S1</span>
                <h2>Modifier infos sante</h2>
                <p>Ces donnees serviront au recalcul automatique de ton IMC.</p>
            </div>

            <?php if (session('errors')): ?>
                <div class="alert alert-error">
                    <?php foreach (session('errors') as $error): ?>
                        <p><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" action="/profil/modifier-infos-sante" method="post">
                <div class="field-grid">
                    <label for="taille">
                        Taille
                        <span class="unit-field">
                            <input type="number" id="taille" name="taille" value="<?= esc(old('taille') ?? ($sante['taille'] ?? '')) ?>" min="80" max="240" required>
                            <span>cm</span>
                        </span>
                    </label>

                    <label for="poids">
                        Poids
                        <span class="unit-field">
                            <input type="number" id="poids" name="poids" value="<?= esc(old('poids') ?? ($sante['poids'] ?? '')) ?>" min="20" max="300" step="0.1" required>
                            <span>kg</span>
                        </span>
                    </label>
                </div>

                <button type="submit" class="primary-button">Recalculer et enregistrer</button>
            </form>
        </section>
    </main>
</body>
</html>
