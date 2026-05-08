<?php
    $regimeNom = (string) ($regime['nom'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activités recommandées</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Activités</p>
            <h1>Activités recommandées</h1>
            <p>
                Régime : <strong><?= esc($regimeNom !== '' ? $regimeNom : '-') ?></strong>
            </p>

            <div class="progress-card">
                <span>Total</span>
                <strong><?= esc((string) count($activites ?? [])) ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/profil">Retour au profil</a>
                <a class="logout-button" href="/logout">Deconnexion</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">AC</span>
                <h2>Liste des activités</h2>
                <p>La durée est affichée en minutes pour chaque activité.</p>
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

            <?php if (empty($activites)): ?>
                <div class="summary-note">
                    Aucune activité n'est associée à ce régime pour le moment.
                </div>
            <?php else: ?>
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Recommandations</h3>
                    </div>

                    <div class="profile-grid">
                        <?php foreach ($activites as $activite): ?>
                            <div class="profile-item">
                                <span class="profile-label"><?= esc($activite['nom'] ?? '-') ?></span>
                                <strong>Durée : <?= esc((string) ($activite['duree'] ?? 0)) ?> min</strong>
                                <?php if (! empty($activite['description'])): ?>
                                    <div class="summary-note" style="margin-top: 10px;">
                                        <?= esc($activite['description']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
