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
    <title>Activités</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <?= view('partials/top_nav'); ?>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Activités">
            <p class="eyebrow">Activités</p>
            <h1>Liste des activités</h1>
            <p>Ajoute, modifie ou supprime des activités sportives.</p>

            <div class="progress-card">
                <span>Total</span>
                <strong><?= esc((string) count($activites ?? [])) ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/profil">Retour profil</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">AC</span>
                <h2>Activités</h2>
                <p>Gestion de la table des activités.</p>
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

            <div class="summary-note" style="margin-bottom: 1rem;">
                <a class="primary-button" href="/activites/nouveau">Ajouter une activité</a>
            </div>

            <?php if (empty($activites)): ?>
                <div class="summary-note">Aucune activité pour le moment.</div>
            <?php else: ?>
                <div class="profile-section">
                    <div class="section-header">
                        <h3>Activités</h3>
                    </div>

                    <div class="profile-grid">
                        <?php foreach ($activites as $a): ?>
                            <?php
                                $id = (int) ($a['id'] ?? 0);
                                $nom = (string) ($a['nom'] ?? '');
                                $duree = (int) ($a['duree'] ?? 0);
                                $description = (string) ($a['description'] ?? '');
                            ?>
                            <div class="profile-item">
                                <span class="profile-label"><?= esc($nom !== '' ? $nom : '-') ?></span>
                                <strong>Durée : <?= esc((string) $duree) ?> min</strong>

                                <?php if ($description !== ''): ?>
                                    <div class="summary-note" style="margin-top: 10px;">
                                        <?= esc($description) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($id > 0): ?>
                                    <div class="summary-note" style="margin-top: 12px; display: flex; gap: .5rem; align-items: center;">
                                        <a href="/activites/<?= esc((string) $id) ?>/modifier">Modifier</a>
                                        <form action="/activites/<?= esc((string) $id) ?>/supprimer" method="post" style="margin: 0;">
                                            <button type="submit" class="logout-button" style="padding: .55rem .85rem;">Supprimer</button>
                                        </form>
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

