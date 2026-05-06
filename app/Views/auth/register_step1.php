<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Etape 1</title>
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-shell">
        <section class="auth-visual" aria-label="Presentation">
            <p class="eyebrow">Regime alimentaire</p>
            <h1>Commencons par creer ton profil.</h1>
            <p>
                Quelques informations suffisent pour preparer une experience
                simple, claire et adaptee a ton objectif.
            </p>

            <div class="progress-card">
                <span>Etape 1 sur 2</span>
                <strong>Identite du compte</strong>
                <div class="progress-track">
                    <div class="progress-fill progress-half"></div>
                </div>
            </div>
        </section>

        <section class="auth-card">
            <a class="back-link" href="/login">Deja inscrit ? Se connecter</a>
            <div class="card-heading">
                <span class="step-pill">01</span>
                <h2>Inscription</h2>
                <p>Renseigne les informations principales de ton compte.</p>
            </div>

            <form class="auth-form" action="/inscription/etape-2" method="get">
                <label for="nom">
                    Nom complet
                    <input type="text" id="nom" name="nom" placeholder="Ex: Maria Rakoto" required>
                </label>

                <label for="email">
                    Email
                    <input type="email" id="email" name="email" placeholder="nom@email.com" required>
                </label>

                <label for="password">
                    Mot de passe
                    <input type="password" id="password" name="password" placeholder="Minimum 8 caracteres" required>
                </label>

                <fieldset class="choice-group">
                    <legend>Genre</legend>
                    <label class="choice-card">
                        <input type="radio" name="genre" value="femme" required>
                        <span>Femme</span>
                    </label>
                    <label class="choice-card">
                        <input type="radio" name="genre" value="homme">
                        <span>Homme</span>
                    </label>
                </fieldset>

                <button type="submit" class="primary-button">Continuer vers les mesures</button>
            </form>
        </section>
    </main>
</body>
</html>
