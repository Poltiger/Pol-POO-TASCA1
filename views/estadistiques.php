<?php $dades = $biblioteca->obtenirEstadistiques(); ?>
<h1>Estadístiques</h1>
<div class="stats-grid">
    <div class="stat-card"><h3>Total materials</h3><span class="number"><?= $dades['total'] ?></span></div>
    <div class="stat-card"><h3>Disponibles</h3><span class="number"><?= $dades['disponibles'] ?></span></div>
    <div class="stat-card"><h3>Prestats</h3><span class="number"><?= $dades['prestats'] ?></span></div>
    <div class="stat-card"><h3>Reservats</h3><span class="number"><?= $dades['reservats'] ?></span></div>
    <div class="stat-card"><h3>Multes pendents</h3><span class="number"><?= number_format($dades['multesPendents'], 2, ',', '.') ?> €</span></div>
    <?php foreach ($dades['perTipus'] as $tipus => $total): ?>
        <div class="stat-card"><h3><?= escapar($tipus) ?></h3><span class="number"><?= $total ?></span></div>
    <?php endforeach; ?>
</div>
