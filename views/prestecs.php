<h1>Préstecs actius</h1>
<table class="table">
    <thead><tr><th>Material</th><th>Usuari</th><th>Data</th><th>Estat</th><th>Accions</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->llistarPrestecsActius() as $prestec): ?>
        <tr>
            <td><?= escapar($prestec->getMaterial()->getTitol()) ?></td>
            <td><?= escapar($prestec->getUsuari()->nom) ?></td>
            <td><?= $prestec->getDataPrestec()->format('d/m/Y') ?></td>
            <td>
                <?php if ($prestec->estaVencut()): ?>
                    <strong class="vencut">Vençut (<?= $prestec->calcularDiesRetard() ?> dies)</strong>
                <?php else: ?>
                    <?= $prestec->getDiesPendents() ?> dies pendents
                <?php endif; ?>
            </td>
            <td><a href="index.php?page=retorn&amp;material_id=<?= $prestec->getMaterial()->getId() ?>">Retornar</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
