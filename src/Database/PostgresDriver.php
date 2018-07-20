<?php

namespace App\Database;


use App\Entity\Note;
use PDO;

class PostgresDriver implements PersisterInterface, FinderInterface
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

    public function findAllNote(): ?array
    {
        $query  = "select id, note from Notes;";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute();

        $notes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        return $notes;
    }

    public function findOneNote(string $id): Note
    {
        $note = new Note();

        $query  = "select note from Notes where id = :id;";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);

        $title = $stmt->fetch(PDO::FETCH_ASSOC)['note'];

        if ($title !== null) {
            $note->id       = $id;
            $note->title    = $title;
        }

        return $note;
    }

    public function deleteNote(string $id): Note
    {
        $note = new Note();

        $query  = "delete from Notes where id = :id returning note;";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);

        $title = $stmt->fetch(PDO::FETCH_ASSOC)['note'];

        if ($title !== null) {
            $note->id       = $id;
            $note->title    = $title;
        }

        return $note;
    }

    public function updateNote(Note $note): bool
    {
        $query  = "update Notes set note = :title where id = :id;";
        $stmt   = $this->connection->prepare($query);

        if ($stmt->execute([':id' => $note->id, ':title' => $note->title])) {
            return true;
        } else {
            return false;
        }
    }
}
