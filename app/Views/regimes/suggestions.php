<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };
    $isGold = isset($user['is_gold']) && (int) $user['is_gold'] === 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestions de regimes</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell suggestions-shell">
        <section class="auth-visual" aria-label="Presentation">
            <p class="eyebrow">Suggestions</p>
            <h1>Des regimes adaptes a ton objectif.</h1>
            <p>
                Objectif : <strong><?= esc($objectifLabel) ?></strong><br>
                IMC : <strong><?= esc($categorieImc['label'] ?? 'IMC non disponible') ?></strong>
            </p>

            <div class="progress-card">
                <span>Statut compte</span>
                <strong><?= $isGold ? 'Gold (-15%)' : 'Standard' ?></strong>
            </div>
        </section>

        <section class="auth-card">
            <a class="back-link" href="/profil">Retour au profil</a>
            <div class="card-heading">
                <span class="step-pill">RG</span>
                <h2>Liste des regimes</h2>
                <p>Filtre automatique selon ton objectif et ta variation de poids.</p>
            </div>

            <?php if (! empty($filtreElargi)): ?>
                <div class="summary-note">
                    Peu de regimes trouves : le filtre a ete elargi pour proposer plus d options.
                </div>
            <?php endif; ?>

            <?php if (empty($regimes)): ?>
                <div class="summary-note">
                    Aucun regime ne correspond a ton objectif pour le moment.
                    <a href="/profil/objectif">Modifier ton objectif</a>
                </div>
            <?php else: ?>
                <div class="suggestions-grid">
                    <?php foreach ($regimes as $regime): ?>
                        <?php
                            $prix = (float) ($regime['prix'] ?? 0);
                            $prixFinal = $isGold ? $prix * 0.85 : $prix;
                        ?>
                        <article class="regime-card">
                            <header>
                                <h3><?= esc($regime['nom'] ?? '-') ?></h3>
                                <span class="badge <?= $isGold ? 'badge-gold' : 'badge-default' ?>">
                                    <?= $isGold ? 'Gold -15%' : 'Standard' ?>
                                </span>
                            </header>

                            <p class="regime-meta">
                                Duree : <strong><?= esc((string) ($regime['duree'] ?? 0)) ?></strong> jours
                            </p>
                            <p class="regime-meta">
                                Variation : <strong><?= esc((string) ($regime['variation_poids'] ?? 0)) ?></strong> kg
                            </p>

                            <div class="price-box">
                                <?php if ($isGold): ?>
                                    <span class="price-old"><?= esc($formatNumber($prix)) ?> Ar</span>
                                <?php endif; ?>
                                <span class="price-new"><?= esc($formatNumber($prixFinal)) ?> Ar</span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
