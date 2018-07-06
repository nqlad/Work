<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

include_once 'config/Database.php';
include_once  'object/Notes.php';

$url= $_SERVER['REQUEST_URI'];
$url = ltrim($url, '/');
$urls = explode('/', $url);

$database = new Database();
$db = $database->getConnection();

$note = new Notes($db);

$notes = $note->readAll();
$countNotes = $notes->rowCount();

if($countNotes <= 0){

    echo json_encode(array("message"=>"No notes found."));
}
if ($urls[1] == null){

    $notesArr = array();
    $notesArr = $notes->fetchAll(PDO::FETCH_KEY_PAIR);
    echo json_encode($notesArr);
}


