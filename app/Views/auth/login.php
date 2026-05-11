<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <?= view('partials/top_nav'); ?>
    <main class="auth-shell login-shell">
        <section class="auth-visual login-visual" aria-label="Presentation">
            <p class="eyebrow">Bon retour</p>
            <h1>Reprends ton suivi la ou tu l'as laisse.</h1>
            <p>
                Connecte-toi pour retrouver ton profil, tes donnees et tes
                prochaines recommandations alimentaires.
            </p>

            <div class="progress-card">
                <span>Session utilisateur</span>
                <strong>Email + mot de passe</strong>
            </div>
        </section>

        <section class="auth-card">
            <div class="card-heading">
                <span class="step-pill">IN</span>
                <h2>Connexion</h2>
                <p>Entre tes identifiants pour acceder a ton espace.</p>
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

            <form class="auth-form" action="/login" method="post">
                <label for="email">
                    Email
                    <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" placeholder="jean@gmail.com" required>
                </label>

                <label for="password">
                    Mot de passe
                    <input type="password" id="password" name="password" placeholder="123456(mdp base)" required>
                </label>

                <div class="form-row">
                    <label class="remember-choice">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="#">Mot de passe oublie ?</a>
                </div>

                <button type="submit" class="primary-button">Se connecter</button>
            </form>

            <div class="admin-access">
                <span>Tu fais partie de l'equipe ?</span>
                <a href="/admin/login">Acceder au login admin</a>
            </div>
        </section>
    </main>
</body>
</html>
