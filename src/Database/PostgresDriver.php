<?php

namespace App\Database;


use App\Entity\Note;
use PDO;

class PostgresDriver implements PersisterInterface
{
    /** @var \PDO */
    private $connection = null;

    public function __construct(string $dsn, string $username, string $password)
    {
        try{
            $this->connection = new \PDO($dsn, $username, $password);
        }catch (\PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }


    public function persist(Note $note): Note
    {
        $query  = "insert into Notes values(default, :note) returning id";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute([':note' => $note->title]);

        $note->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        return $note;
    }
}
