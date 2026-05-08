<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objectif utilisateur</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Objectif</p>
            <h1>Choisis ton objectif.</h1>
            <p>
                Ton objectif aide l'application à adapter les recommandations selon ton IMC.
            </p>

            <div class="progress-card">
                <span>IMC</span>
                <strong><?= isset($imc) && $imc !== null && $imc > 0 ? esc((string) $imc) : '-' ?></strong>
            </div>

            <?php if (isset($impact) && $impact !== null): ?>
                <div class="progress-card">
                    <span>Catégorie</span>
                    <strong><?= esc($impact['categoryLabel'] ?? '-') ?></strong>
                </div>
            <?php endif; ?>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/profil">Retour au profil</a>
                <a class="logout-button" href="/logout">Deconnexion</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">OB</span>
                <h2>Objectif utilisateur</h2>
                <p>Sélectionne un objectif puis enregistre.</p>
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

            <form class="auth-form" action="/profil/objectif" method="post">
                <?= csrf_field() ?>

                <div class="profile-section">
                    <div class="section-header">
                        <h3>Choix de l'objectif</h3>
                    </div>

                    <div class="profile-grid">
                        <?php foreach (($objectifs ?? []) as $key => $label): ?>
                            <div class="profile-item">
                                <label class="profile-label" for="objectif-<?= esc($key) ?>">
                                    <input
                                        type="radio"
                                        id="objectif-<?= esc($key) ?>"
                                        name="objectif"
                                        value="<?= esc($key) ?>"
                                        <?= (isset($objectifActuel) && $objectifActuel === $key) ? 'checked' : '' ?>
                                        required
                                    >
                                    <?= esc($label) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="primary-button">Enregistrer l'objectif</button>
            </form>

            <?php if (isset($impact) && $impact !== null): ?>
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Impact (selon IMC)</h3>
                    </div>
                    <div class="summary-note">
                        <strong><?= esc($impact['title'] ?? '') ?></strong><br>
                        <?= esc($impact['details'] ?? '') ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
