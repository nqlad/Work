<?php
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


    /**
     * @return PDOStatement
     */
    function readAll()
    {
        $query = "select id, note from Notes;";
        //$executeQuery = $this->connect->prepare($query);
        //$executeQuery->bindParam('id', 1);
        //$executeQuery->execute();

        $stmt = $this->connect->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}