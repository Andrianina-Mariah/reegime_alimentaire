<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Régimes</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Régimes</p>
            <h1>Liste des régimes</h1>
            <p>Choisis un régime pour voir les activités recommandées.</p>

            <div class="progress-card">
                <span>Total</span>
                <strong><?= esc((string) count($regimes ?? [])) ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/profil">Retour au profil</a>
                <a class="logout-button" href="/logout">Deconnexion</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">RG</span>
                <h2>Régimes disponibles</h2>
                <p>Accès rapide aux activités par régime.</p>
            </div>

            <?php if (session('errors')): ?>
                <div class="alert alert-error">
                    <?php foreach (session('errors') as $error): ?>
                        <p><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($regimes)): ?>
                <div class="summary-note">Aucun régime disponible pour le moment.</div>
            <?php else: ?>
                <div class="suggestions-grid">
                    <?php foreach ($regimes as $regime): ?>
                        <?php
                            $id = (int) ($regime['id'] ?? 0);
                            $nom = (string) ($regime['nom'] ?? '');
                            $duree = (int) ($regime['duree'] ?? 0);
                            $prix = (float) ($regime['prix'] ?? 0);
                        ?>
                        <article class="regime-card">
                            <header>
                                <h3><?= esc($nom !== '' ? $nom : '-') ?></h3>
                                <span class="badge badge-default">Standard</span>
                            </header>

                            <p class="regime-meta">Durée : <strong><?= esc((string) $duree) ?></strong> jours</p>
                            <div class="price-box">
                                <span class="price-new"><?= esc($formatNumber($prix)) ?> Ar</span>
                            </div>

                            <?php if ($id > 0): ?>
                                <div class="summary-note">
                                    <a href="/regimes/<?= esc((string) $id) ?>/activites">Voir activités recommandées</a>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
