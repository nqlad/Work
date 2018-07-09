<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

$loader = require(__DIR__ . '/../vendor/autoload.php');
$loader->addPsr4('project\\', __DIR__ . '/src/');

$url    = $_SERVER['REQUEST_URI'];
$url    = ltrim($url, '/');
$urls   = explode('/', $url);

$database = new App\Controller\Database();
$db = $database->getConnection();

$note = new App\Model\Notes($db);

$notes = $note->readAll();
$countNotes = $notes->rowCount();

if ($countNotes <= 0) {
    echo json_encode(array("message" => "No notes found."));
}

if ($urls[1] == null) {
    $notesArr = array();
    $notesArr = $notes->fetchAll(PDO::FETCH_KEY_PAIR);

    echo json_encode($notesArr);
}
