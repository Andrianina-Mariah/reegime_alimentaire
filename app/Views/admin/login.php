<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <?= view('partials/top_nav'); ?>
    <main class="auth-shell login-shell">
        <section class="auth-visual admin-visual login-visual" aria-label="Presentation admin">
            <p class="eyebrow">Administration</p>
            <h1>Pilote le regime depuis les coulisses.</h1>
            <p>
                Acces reserve aux administrateurs pour gerer les donnees,
                les regimes, les codes et les utilisateurs.
            </p>

            <div class="progress-card">
                <span>Espace securise</span>
                <strong>Login admin</strong>
            </div>
        </section>

        <section class="auth-card">
            <a class="back-link" href="/login">Retour a la connexion</a>
            <div class="card-heading">
                <span class="step-pill">AD</span>
                <h2>Admin</h2>
                <p>Entre tes identifiants administrateur pour continuer.</p>
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

            <form class="auth-form" action="/admin/login" method="post">
                <label for="email">
                    Email admin
                    <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" placeholder="admin@regime.local" required>
                </label>

                <label for="password">
                    Mot de passe
                    <input type="password" id="password" name="password" placeholder="Mot de passe admin" required>
                </label>

                <div class="summary-note">
                    <strong>Compte initial :</strong>
                    <code>admin@regime.local</code> / <code>admin12345</code>
                </div>

                <button type="submit" class="primary-button">Entrer dans l'administration</button>
            </form>
        </section>
    </main>
</body>
</html>
