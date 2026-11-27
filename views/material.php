<?php
$material = $biblioteca->cercarPerId((int) ($_GET['id'] ?? 0));
if ($material === null) {
    throw new App\Exceptions\MaterialNoTrobatException('No s’ha trobat el material.');
}
?>
<h1><?= escapar($material->getTitol()) ?></h1>
<ul class="list-group">
    <li class="list-item"><?= escapar((string) $material) ?></li>
    <?php if ($material instanceof App\Models\Llibre): ?>
        <li class="list-item">Pàgines: <?= $material->getNumeroPagines() ?></li>
    <?php elseif ($material instanceof App\Models\Revista): ?>
        <li class="list-item">Edició: <?= $material->getNumeroEdicio() ?></li>
    <?php elseif ($material instanceof App\Models\DVD): ?>
        <li class="list-item">Durada: <?= escapar($material->getDuracioFormatada()) ?></li>
    <?php endif; ?>
    <?php if ($material instanceof App\Interfaces\Reservable && $material->estaReservat()): ?>
        <li class="list-item">Reservat per <?= escapar($material->getUsuariReserva()) ?></li>
    <?php endif; ?>
</ul>

<form method="get" action="index.php">
    <input type="hidden" name="material_id" value="<?= $material->getId() ?>">
    <label for="usuari_id">Usuari</label>
    <select id="usuari_id" name="usuari_id">
        <?php foreach ($biblioteca->getUsuaris() as $usuari): ?>
            <option value="<?= $usuari->id ?>"><?= escapar($usuari->nom) ?></option>
        <?php endforeach; ?>
    </select>
    <button name="page" value="prestec">Prestar</button>
    <button name="page" value="reserva">Reservar</button>
</form>
<?php if (!$material->isDisponible()): ?>
    <p><a href="index.php?page=retorn&amp;material_id=<?= $material->getId() ?>">Retornar</a></p>
<?php endif; ?>

<h2>Historial d'auditoria</h2>
<?php if ($material->obtenirHistorial() === []): ?>
    <p>No hi ha accions.</p>
<?php endif; ?>
<ul class="list-group">
    <?php foreach ($material->obtenirHistorial() as $entrada): ?>
        <li class="list-item"><?= escapar($entrada['data'] . ' · ' . $entrada['accio'] . ' ' . $entrada['detalls']) ?></li>
    <?php endforeach; ?>
</ul>
