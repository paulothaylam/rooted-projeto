<?php

declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
session_start();

require_once __DIR__ . '/controller/HomeController.php';

$pagina = isset($_GET['pagina']) ? (string) $_GET['pagina'] : 'home';
(new HomeController())->mostrar($pagina);

