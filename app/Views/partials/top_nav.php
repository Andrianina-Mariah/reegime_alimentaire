<nav class="app-navbar" role="navigation" aria-label="Navigation principale">
    <div class="nav-inner">
        <a class="nav-brand" href="<?= session('is_logged_in') ? '/profil' : '/login' ?>">
            Regime alimentaire
        </a>
        <div class="nav-actions">
            <?php if (session('is_logged_in')): ?>
                <a class="nav-link" href="/profil">Profil</a>
                <a class="nav-button" href="/logout">Déconnexion</a>
            <?php else: ?>
                <a class="nav-link" href="/login">Connexion</a>
                <a class="nav-button" href="/inscription">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
