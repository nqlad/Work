<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

//header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once  '../object/Notes.php';

$database = new Database();
$db = $database->getConnection();

$note = new Notes($db);

$notes = $note->read();
$countNotes = $notes->rowCount();

if($countNotes > 0){

    $notesArr = array();
    $notesArr["id"] = array();

    $rowS = $notes->fetchAll(PDO::FETCH_ASSOC);

        extract($row);

        $notes_id=array(
          "id"=>$id,
          "note"=>$note
        );
        array_push($notesArr["id"],$notes_id);

    echo json_encode($notesArr);
}
else{
    echo json_encode(array("message"=>"No notes found."));
}
