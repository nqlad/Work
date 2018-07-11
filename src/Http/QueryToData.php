<?php

namespace App\Http;


use App\Model\Database;
use App\Model\Notes;
use PDO;

class QueryToData
{
    private $data;

    public function createData($action,$id = null): array
    {
        $connect = new Database();
        $db = $connect->getConnection();

        $note = new Notes($db);
        $notes = $note->$action($id);

        $countNotes = $notes->rowCount();
        if ($countNotes <= 0) {
            $this->data = ["message" => "No notes found."];
            return $this->data;
        }

        $this->data = $notes->fetchAll(PDO::FETCH_KEY_PAIR);

        return $this->data;
    }
}
