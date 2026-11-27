<?php $multa = $biblioteca->retornarMaterial((int) ($_GET['material_id'] ?? 0)); ?>
<h1>Retorn</h1>
<p>Material retornat.</p>
<?php if ($multa > 0): ?>
    <p>Multa: <?= number_format($multa, 2, ',', '.') ?> €</p>
<?php endif; ?>
