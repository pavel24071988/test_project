<?php
session_start();
session_write_close();

require __DIR__ . '/../components/db.php';
require __DIR__ . '/../models/User.php';
require __DIR__ . '/../controllers/SiteController.php';

function setSessionVar($id, $value)
{
    session_start();
    $_SESSION[$id] = $value;
    session_write_close();
}
function setFlash($id, $value)
{
    $id = 'flash-' . $id;
    setSessionVar($id, $value);
}
function flash($id)
{
    $id = 'flash-' . $id;
    if (isset($_SESSION[$id])) {
        $value = $_SESSION[$id];
        session_start();
        unset($_SESSION[$id]);
        session_write_close();
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

echo $c->getContent();
