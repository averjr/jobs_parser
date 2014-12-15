<?php
ini_set("default_charset", 'utf-8');
// load and initialize any global libraries
require_once 'model.php';
require_once 'controllers.php';
// route the request internally
$uri = $_SERVER['REQUEST_URI'];

if ($uri == '/index.php' || $uri == '/') {
    if (!empty($_POST)) {
        update_action();
    }
    show_action();
} elseif ($uri == '/index.php/edit') {
    edit_action();
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Page Not Found</h1></body></html>';
}