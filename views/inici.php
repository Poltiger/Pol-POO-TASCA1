<h1><?= escapar($biblioteca->getNom()) ?></h1>
<ul class="list-group">
    <li class="list-item">Materials: <?= count($biblioteca->getMaterials()) ?></li>
    <li class="list-item">Usuaris: <?= count($biblioteca->getUsuaris()) ?></li>
    <li class="list-item">Préstecs actius: <?= count($biblioteca->llistarPrestecsActius()) ?></li>
    <li class="list-item">Reserves: <?= count($biblioteca->llistarReserves()) ?></li>
</ul>
