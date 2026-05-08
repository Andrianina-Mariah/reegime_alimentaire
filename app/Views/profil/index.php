<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Espace personnel</p>
            <h1>Ton profil au complet, en un coup d'œil.</h1>
            <p>
                Retrouve tes informations principales ainsi que tes mesures de sante
                pour suivre l'evolution de ton IMC et de tes objectifs.
            </p>

            <div class="progress-card">
                <span>Statut compte</span>
                <strong><?= isset($user['is_gold']) && (int) $user['is_gold'] === 1 ? 'Gold actif' : 'Standard' ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/">Retour a l'accueil</a>
                <a class="logout-button" href="/logout">Deconnexion</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">PR</span>
                <h2>Profil utilisateur</h2>
                <p>Voici les informations du compte actuellement connecte.</p>
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

            <div class="profile-actions">
                <a class="profile-action-card profile-action-perso" href="/profil/modifier-infos-perso">
                    <span>Infos utilisateur</span>
                    <strong>Modifier le nom, l'email et le genre</strong>
                </a>
                <a class="profile-action-card profile-action-sante" href="/profil/modifier-infos-sante">
                    <span>Infos sante</span>
                    <strong>Modifier la taille et le poids</strong>
                </a>
                <a class="profile-action-card profile-action-sante" href="/profil/objectif">
                    <span>Objectif</span>
                    <strong>Choisir ton objectif et voir l'impact</strong>
                </a>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h3>Informations personnelles</h3>
                </div>
                <div class="profile-grid">
                    <div class="profile-item">
                        <span class="profile-label">Nom</span>
                        <strong><?= esc($user['nom'] ?? '-') ?></strong>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Email</span>
                        <strong><?= esc($user['email'] ?? '-') ?></strong>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Genre</span>
                        <strong><?= esc(ucfirst($user['genre'] ?? '')) ?: '-' ?></strong>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Statut</span>
                        <strong class="badge <?= isset($user['is_gold']) && (int) $user['is_gold'] === 1 ? 'badge-gold' : 'badge-default' ?>">
                            <?= isset($user['is_gold']) && (int) $user['is_gold'] === 1 ? 'Gold' : 'Standard' ?>
                        </strong>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h3>Informations sante</h3>
                </div>
                <div class="profile-grid">
                    <div class="profile-item">
                        <span class="profile-label">Taille</span>
                        <strong><?= isset($sante['taille']) ? esc($sante['taille']) . ' cm' : '-' ?></strong>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Poids</span>
                        <strong><?= isset($sante['poids']) ? esc($sante['poids']) . ' kg' : '-' ?></strong>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">IMC</span>
                        <strong><?= isset($sante['imc']) ? esc($sante['imc']) : '-' ?></strong>
                    </div>
                </div>
            </div>

            <div class="summary-note">
                Choisis le bouton correspondant pour modifier uniquement la partie souhaitee de ton profil.
            </div>
        </section>
    </main>
</body>
</html>
