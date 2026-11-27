<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Data\DataGenerator;
use App\Exceptions\MaterialNoDisponibleException;

session_start();
$pagina = $_GET['page'] ?? 'inici';

if ($pagina === 'reset') {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['biblioteca'])) {
    $_SESSION['biblioteca'] = DataGenerator::crear();
}
$biblioteca = $_SESSION['biblioteca'];

function escapar(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

ob_start();
try {
    $vista = match ($pagina) {
        'inici' => 'views/inici.php',
        'materials' => 'views/materials.php',
        'material' => 'views/material.php',
        'usuaris' => 'views/usuaris.php',
        'usuari' => 'views/usuari.php',
        'prestec' => 'views/prestec.php',
        'retorn' => 'views/retorn.php',
        'prestecs' => 'views/prestecs.php',
        'reserva' => 'views/reserva.php',
        'reserves' => 'views/reserves.php',
        'estadistiques' => 'views/estadistiques.php',
        'auditoria' => 'views/auditoria.php',
        default => 'views/404.php',
    };
    require __DIR__ . '/' . $vista;
} catch (MaterialNoDisponibleException $excepcio) {
    ob_clean();
    $error = (string) $excepcio;
    require __DIR__ . '/views/error.php';
} catch (Exception $excepcio) {
    ob_clean();
    $error = $excepcio->getMessage();
    require __DIR__ . '/views/error.php';
}
$contingut = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <title>Biblioteca digital</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .navbar a { display: inline-block; margin: 0 12px 8px 0; }
        .table { border-collapse: collapse; }
        .table th, .table td { border: 1px solid #bbb; padding: 7px; text-align: left; }
        .list-item { margin-bottom: 5px; }
        .stat-card { display: inline-block; padding: 10px; margin: 4px; border: 1px solid #bbb; }
        .stat-card h3 { margin: 0 0 6px; }
        .vencut { color: darkred; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="index.php">Inici</a>
    <a href="index.php?page=materials">Materials</a>
    <a href="index.php?page=usuaris">Usuaris</a>
    <a href="index.php?page=prestecs">Préstecs</a>
    <a href="index.php?page=reserves">Reserves</a>
    <a href="index.php?page=estadistiques">Estadístiques</a>
    <a href="index.php?page=auditoria">Auditoria</a>
</nav>
<main>
    <?= $contingut ?>
</main>
</body>
</html>
