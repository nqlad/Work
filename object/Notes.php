<?php
/**
 * Created by PhpStorm.
 * User: heify
 * Date: 05.07.18
 * Time: 17:10
 */

class Notes
{
    /** @var PDO */
    private $connect;

    /** @var int */
    private $id;

    private $note;

    public function __construct($db)
    {
        $this->connect = $db;
    }


    /**
     * @return PDOStatement
     */
    function read()
    {
        $query = "select * from Notes;";
        //$executeQuery = $this->connect->prepare($query);
        //$executeQuery->bindParam('id', 1);
        //$executeQuery->execute();

        $stmt = $this->connect->prepare($query);
        $stmt->execute([1]);

        var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));die();

        //return $executeQuery;
    }
}