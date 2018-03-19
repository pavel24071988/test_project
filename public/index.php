<?php
session_start();

require_once __DIR__ . '/../components/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../controllers/SiteController.php';

function setFlash($id, $value)
{
    $id = 'flash-' . $id;
    $_SESSION[$id] = $value;
}
function flash($id)
{
    $id = 'flash-' . $id;
    if (isset($_SESSION[$id])) {
        $value = $_SESSION[$id];
        unset($_SESSION[$id]);
        return $value;
    }

    return null;
}

$action = $_GET['action'] ?? 'cash';
if ($action == 'auth' && isset($_SESSION['user'])) {
    $action = 'cash';
}
if (!isset($_SESSION['user'])) {
    $action = 'auth';
}

$c = new SiteController($action);
