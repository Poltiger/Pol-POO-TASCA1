<h1>Usuaris</h1>
<table class="table">
    <thead><tr><th>ID</th><th>Nom</th><th>Correu</th><th>Accions</th></tr></thead>
    <tbody>
    <?php foreach ($biblioteca->getUsuaris() as $usuari): ?>
        <tr>
            <td><?= $usuari->id ?></td>
            <td><?= escapar($usuari->nom) ?></td>
            <td><?= escapar($usuari->email) ?></td>
            <td><a href="index.php?page=usuari&amp;id=<?= $usuari->id ?>">Perfil</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
