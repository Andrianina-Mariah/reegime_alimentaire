<h1>Liste des produits</h1>

<?php if (! empty($produits)): ?>
    <ul>
        <?php foreach ($produits as $produit): ?>
            <li>
                <strong><?= esc($produit['nom']) ?></strong>
                — <?= number_format((float) $produit['prix'], 2, ',', ' ') ?> €
                <?php if (! empty($produit['description'])): ?>
                    <br>
                    <small><?= esc($produit['description']) ?></small>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun produit n’est disponible pour le moment.</p>
<?php endif; ?>