<h1>Auditoria</h1>
<table class="table">
    <thead><tr><th>Material</th><th>Data</th><th>Acció</th><th>Detalls</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->getMaterials() as $material): ?>
        <?php foreach ($material->obtenirHistorial() as $entrada): ?>
            <tr>
                <td><?= escapar($material->getTitol()) ?></td>
                <td><?= escapar($entrada['data']) ?></td>
                <td><?= escapar($entrada['accio']) ?></td>
                <td><?= escapar($entrada['detalls']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>
