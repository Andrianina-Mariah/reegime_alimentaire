<?php
    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };
    $solde = (float) ($wallet['solde'] ?? 0);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <?= view('partials/top_nav'); ?>
    <main class="auth-shell profile-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Wallet</p>
            <h1>Gère ton solde</h1>
            <p>Ajoute de l'argent avec un code et suis ton solde.</p>

            <div class="progress-card">
                <span>Solde actuel</span>
                <strong><?= esc($formatNumber($solde)) ?> Ar</strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="profile-topbar">
                <a class="back-link" href="/profil">Retour au profil</a>
            </div>

            <div class="card-heading">
                <span class="step-pill">WL</span>
                <h2>Ajouter un code</h2>
                <p>Saisis un code valide pour recharger ton wallet.</p>
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

            <form class="auth-form" action="/wallet/recharge" method="post">
                <label for="code">
                    Code de recharge
                    <input type="text" id="code" name="code" value="<?= esc(old('code')) ?>" placeholder="CODE001" required>
                </label>

                <button type="submit" class="primary-button">Ajouter le montant</button>
            </form>

            <div class="summary-note" style="margin-top: 1.5rem;">
                Un code ne peut être utilisé qu'une seule fois.
            </div>
        </section>
    </main>
</body>
</html>
