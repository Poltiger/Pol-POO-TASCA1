<?php
$materials = match ($_GET['type'] ?? '') {
    'llibre' => $biblioteca->getLlibres(),
    'revista' => $biblioteca->getRevistes(),
    'dvd' => $biblioteca->getDVDs(),
    default => $biblioteca->getMaterials(),
};
?>
<h1>Materials</h1>
<p>
    <a href="index.php?page=materials">Tots</a> |
    <a href="index.php?page=materials&amp;type=llibre">Llibres</a> |
    <a href="index.php?page=materials&amp;type=revista">Revistes</a> |
    <a href="index.php?page=materials&amp;type=dvd">DVD</a>
</p>
<table class="table">
    <thead><tr><th>ID</th><th>Títol</th><th>Tipus</th><th>Estat</th><th>Accions</th></tr></thead>
    <tbody>
    <?php foreach ($materials as $material): ?>
        <tr>
            <td><?= $material->getId() ?></td>
            <td><?= escapar($material->getTitol()) ?></td>
            <td><?= escapar($material->getTipus()) ?></td>
            <td><?= $material->isDisponible() ? 'Disponible' : 'Prestat' ?></td>
            <td><a href="index.php?page=material&amp;id=<?= $material->getId() ?>">Detall</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
