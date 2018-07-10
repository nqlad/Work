<?php

namespace App\Controller;

use PDO;

class Controller
{
    private $method;

    public function __construct($mtd)
    {
        $this->method = $mtd;
    }

    function start()
    {
        $url    = $_SERVER['REQUEST_URI'];
        $url    = ltrim($url, '/');
        $urls   = explode('/', $url);

        $database   = new \App\Model\Database();
        $db         = $database->getConnection();

        $note = new \App\Model\Notes($db);

        $notes      = $note->readAll();

        if ($this->method == 'GET'){
            $this->requestGet($notes,$urls);
        }
        if ($this->method == 'POST'){
            echo 'gdfgd';
        }
    }

    function requestGet($notes,$urls){

        $countNotes = $notes->rowCount();

        if ($countNotes <= 0) {
            echo json_encode(array("message" => "No notes found."));
            return;
        }

        if ($urls[1] == null) {
            $notesArr = array();
            $notesArr = $notes->fetchAll(PDO::FETCH_KEY_PAIR);

            echo json_encode($notesArr);
        } else {
            $noteId = $urls[2];
            echo json_encode(array(
                'id'=>$noteId,

            ));
        }


    }
}
