<h1>Reserves actives</h1>
<table class="table">
    <thead><tr><th>Material</th><th>Reservat per</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->llistarReserves() as $material): ?>
        <tr><td><?= escapar($material->getTitol()) ?></td><td><?= escapar($material->getUsuariReserva()) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
