<?php
$prestec = $biblioteca->prestarMaterial((int) ($_GET['material_id'] ?? 0), (int) ($_GET['usuari_id'] ?? 0));
$usuari = $prestec->getUsuari();
?>
<h1>Préstec</h1>
<?php foreach ($usuari->obtenirNotificacions() as $missatge): ?>
    <p><?= escapar($missatge) ?></p>
<?php endforeach; ?>
<p><?= escapar($prestec->getMaterial()->getTitol()) ?> prestat a <?= escapar($usuari->nom) ?>.</p>
<p><a href="index.php?page=usuari&amp;id=<?= $usuari->id ?>">Veure el perfil</a></p>
