<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };
    $isGold = isset($user['is_gold']) && (int) $user['is_gold'] === 1;
    $discountRate = (float) ($goldDetails['discountRate'] ?? 0.15);
    $discountLabel = (string) ($goldDetails['discountLabel'] ?? '-15%');
    $goldPrice = (float) ($goldDetails['price'] ?? 0);
    $goldAccess = (string) ($goldDetails['accessMode'] ?? 'Paiement unique');
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
            <p>
                Objectif : <strong><?= esc($objectifLabel ?? 'Maintien') ?></strong><br>
                IMC : <strong><?= esc($categorieImc['label'] ?? 'IMC non disponible') ?></strong>
            </p>
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
                <p>Filtrés automatiquement selon la variation de poids.</p>
            </div>

            <div class="summary-note" style="margin-bottom: 1rem;">
                Option Gold : <strong><?= esc($formatNumber($goldPrice)) ?> Ar</strong> (<?= esc($goldAccess) ?>)
                <br>Remise appliquée : <?= esc($discountLabel) ?> sur tous les régimes.
                <br>
                <?php if ($isGold): ?>
                    <strong>Statut :</strong> Gold actif
                <?php else: ?>
                    <form action="/gold/activer" method="post" style="margin-top: .5rem;">
                        <button type="submit" class="primary-button">Activer Gold</button>
                        <a class="back-link" href="/wallet" style="margin-left: .5rem;">Recharger wallet</a>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (! empty($filtreElargi)): ?>
                <div class="summary-note" style="margin-bottom: 1rem;">
                    Peu de régimes trouvés : le filtre a été élargi pour proposer plus d'options.
                </div>
            <?php endif; ?>

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
                            $prixFinal = $isGold ? $prix * (1 - $discountRate) : $prix;
                        ?>
                        <article class="regime-card">
                            <header>
                                <h3><?= esc($nom !== '' ? $nom : '-') ?></h3>
                                <span class="badge <?= $isGold ? 'badge-gold' : 'badge-default' ?>">
                                    <?= $isGold ? 'Gold ' . esc($discountLabel) : 'Standard' ?>
                                </span>
                            </header>

                            <p class="regime-meta">Durée : <strong><?= esc((string) $duree) ?></strong> jours</p>
                            <div class="price-box">
                                <?php if ($isGold): ?>
                                    <span class="price-old"><?= esc($formatNumber($prix)) ?> Ar</span>
                                <?php endif; ?>
                                <span class="price-new"><?= esc($formatNumber($prixFinal)) ?> Ar</span>
                            </div>

                            <?php if ($id > 0): ?>
                                <div class="summary-note">
                                    <a href="/regimes/<?= esc((string) $id) ?>/activites">Voir activités recommandées</a>
                                    <br>
                                    <a href="/regimes/<?= esc((string) $id) ?>/pdf">Exporter PDF</a>
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
