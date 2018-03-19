<?php
session_start();

require_once __DIR__ . '/../components/db.php';
require_once __DIR__ . '/../controllers/SiteController.php';


$action = $_GET['action'] ?? 'cash';
if ($action == 'auth' && isset($_SESSION['user'])) {
    $action = 'cash';
}
if (!isset($_SESSION['user'])) {
    $action = 'auth';
}

$c = new SiteController($action);
