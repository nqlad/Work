<?php

namespace App\Model;

use PDO, PDOStatement;

class Notes
{
    /** @var PDO */
    private $connect;

    private $id;

    private $note;

    public function __construct($db)
    {
        $this->connect = $db;
    }

    function read(){

    }

    function update(){

    }

    function delete(){

    }


    /**
     * @return PDOStatement
     */
    function readAll()
    {
        $query = "select id, note from Notes;";
        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readById($id)
    {
        $query = "select id, note from Notes where id = ?";
        $stmt = $this->connect->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }
}
