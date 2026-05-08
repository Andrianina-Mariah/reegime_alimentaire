<?php
    $maxValue = static function (array $items): float {
        $values = array_map(static fn ($item) => (float) ($item['total'] ?? 0), $items);
        return max($values ?: [1]) ?: 1;
    };

    $formatNumber = static function ($value): string {
        return number_format((float) $value, 0, ',', ' ');
    };

    $renderBars = static function (array $items, string $unit = '') use ($maxValue, $formatNumber): void {
        $max = $maxValue($items);
        foreach ($items as $item):
            $label = (string) ($item['label'] ?? '-');
            $total = (float) ($item['total'] ?? 0);
            $width = $max > 0 ? max(4, ($total / $max) * 100) : 4;
?>
            <div class="bar-row">
                <div class="bar-meta">
                    <span><?= esc(ucfirst($label)) ?></span>
                    <strong><?= esc($formatNumber($total)) ?><?= esc($unit) ?></strong>
                </div>
                <div class="bar-track">
                    <span style="width: <?= esc((string) $width) ?>%"></span>
                </div>
            </div>
<?php
        endforeach;
    };
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <main class="dashboard-shell">
        <header class="dashboard-hero">
            <div>
                <p class="eyebrow">Administration</p>
                <h1>Dashboard regime alimentaire</h1>
                <p>Vue rapide sur les utilisateurs, les regimes, les activites et les revenus.</p>
            </div>
            <a href="/admin/logout" class="logout-link">Deconnexion admin</a>
        </header>

        <section class="stats-grid" aria-label="Compteurs">
            <article class="stat-card">
                <span>Utilisateurs</span>
                <strong><?= esc((string) $stats['utilisateurs']) ?></strong>
                <p>Comptes inscrits</p>
            </article>
            <article class="stat-card stat-card-warm">
                <span>Regimes</span>
                <strong><?= esc((string) $stats['regimes']) ?></strong>
                <p>Programmes disponibles</p>
            </article>
            <article class="stat-card stat-card-dark">
                <span>Activites</span>
                <strong><?= esc((string) $stats['activites']) ?></strong>
                <p>Activites proposees</p>
            </article>
        </section>

        <section class="charts-grid">
            <article class="chart-card">
                <div class="chart-heading">
                    <span>Utilisateurs</span>
                    <h2>Repartition par genre</h2>
                </div>
                <?php $renderBars($charts['utilisateursGenre']); ?>
            </article>

            <article class="chart-card">
                <div class="chart-heading">
                    <span>Utilisateurs</span>
                    <h2>Standard vs Gold</h2>
                </div>
                <?php $renderBars($charts['utilisateursStatut']); ?>
            </article>

            <article class="chart-card chart-card-wide">
                <div class="chart-heading">
                    <span>Regimes</span>
                    <h2>Prix par regime</h2>
                </div>
                <?php $renderBars($charts['regimesPrix'], ' Ar'); ?>
            </article>

            <article class="chart-card chart-card-wide">
                <div class="chart-heading">
                    <span>Revenus</span>
                    <h2>Indicateurs financiers</h2>
                </div>
                <?php $renderBars($charts['revenus'], ' Ar'); ?>
            </article>
        </section>
    </main>
</body>
</html>
