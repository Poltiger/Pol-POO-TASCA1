<?php
$reservat = $biblioteca->reservarMaterial((int) ($_GET['material_id'] ?? 0), (int) ($_GET['usuari_id'] ?? 0));
?>
<h1>Reserva</h1>
<p><?= $reservat ? 'Reserva feta.' : 'El material ja està reservat.' ?></p>
<p><a href="index.php?page=reserves">Veure les reserves</a></p>
