<?php
$usuari = $biblioteca->cercarUsuari((int) ($_GET['id'] ?? 0));
if ($usuari === null) {
    throw new App\Exceptions\UsuariNoTrobatException('No s’ha trobat l’usuari.');
}
?>
<h1><?= escapar($usuari->nom) ?></h1>
<ul class="list-group">
    <li class="list-item">Correu: <?= escapar($usuari->email) ?></li>
    <li class="list-item">Data de registre: <?= $usuari->dataRegistre->format('d/m/Y') ?></li>
</ul>
<?php if (($_GET['action'] ?? '') === 'prestecs'): ?>
    <h2>Historial de préstecs</h2>
    <ul class="list-group">
        <?php foreach ($biblioteca->llistarPrestecsUsuari($usuari->id) as $prestec): ?>
            <li class="list-item">
                <?= escapar($prestec->getMaterial()->getTitol()) ?>,
                <?= $prestec->getDataPrestec()->format('d/m/Y') ?>,
                <?= $prestec->estaActiu() ? 'Actiu' : 'Retornat el ' . $prestec->getDataRetorn()->format('d/m/Y') ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <h2>Préstecs actius</h2>
    <ul class="list-group">
        <?php foreach ($usuari->getMaterialsPrestats() as $material): ?>
            <li class="list-item"><?= escapar($material->getTitol()) ?></li>
        <?php endforeach; ?>
    </ul>
    <p><a href="index.php?page=usuari&amp;id=<?= $usuari->id ?>&amp;action=prestecs">Historial de préstecs</a></p>
<?php endif; ?>
