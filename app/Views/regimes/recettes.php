<?php
    $regimeNom = (string) ($regime['nom'] ?? '');
    $grouped = [];
    foreach (($recettes ?? []) as $recette) {
        $jour = (int) ($recette['jour'] ?? 0);
        if (! isset($grouped[$jour])) {
            $grouped[$jour] = [];
        }
        $grouped[$jour][] = $recette;
    }
    ksort($grouped);

    $days = range(1, 30);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes du régime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/auth.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body>
    <main class="auth-shell profile-shell container-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Recettes</p>
            <h1>Recettes du régime</h1>
            <p>
                Régime : <strong><?= esc($regimeNom !== '' ? $regimeNom : '-') ?></strong>
            </p>

            <div class="progress-card">
                <span>Total</span>
                <strong><?= esc((string) count($recettes ?? [])) ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link btn btn-light btn-sm" href="/regimes">Retour aux régimes</a>
                <a class="logout-button btn btn-dark btn-sm" href="/logout">Déconnexion</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">RC</span>
                <h2>Plan de recettes</h2>
                <p>Une sélection variée sur la durée du régime.</p>
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

            <?php if (empty($recettes)): ?>
                <div class="summary-note">
                    Aucune recette associée à ce régime pour le moment.
                </div>
            <?php else: ?>
                <div class="calendar-grid" aria-label="Calendrier 30 jours">
                    <?php foreach ($days as $day): ?>
                        <?php $items = $grouped[$day] ?? []; ?>
                        <article class="calendar-day">
                            <header class="calendar-day-header">
                                <span>Jour <?= esc((string) $day) ?></span>
                                <span class="badge badge-default">
                                    <?= esc((string) count($items)) ?> recette(s)
                                </span>
                            </header>

                            <?php if (empty($items)): ?>
                                <p class="calendar-empty">Aucune recette</p>
                            <?php else: ?>
                                <ul class="calendar-items">
                                    <?php foreach ($items as $recette): ?>
                                        <li>
                                            <strong><?= esc($recette['nom'] ?? '-') ?></strong>
                                            <span><?= esc($recette['type_repas'] ?? 'repas') ?></span>
                                            <p><?= esc($recette['description'] ?? '-') ?></p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
