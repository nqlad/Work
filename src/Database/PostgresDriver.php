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
        $query  = "insert into Notes values(default, :title) returning id";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute([':title' => $note->title]);

        $note->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        return $note;
    }

    public function findAllNote(): ?array
    {
        $query  = "select id, title from Notes;";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute();

        $notes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        return $notes;
    }

    public function findOneNote(string $id): ?Note
    {
        $query  = "select * from Notes where id = :id;";
        $statement   = $this->connection->prepare($query);
        $statement->setFetchMode(PDO::FETCH_CLASS, Note::class);
        $statement->execute([':id' => $id]);

        $note = $statement->fetch(PDO::FETCH_CLASS);

        if(!$note){
            $note = null;
        }

        return $note;
    }

    public function deleteNote(string $id): Note
    {
        $note = new Note();

        $query  = "delete from Notes where id = :id returning title;";
        $stmt   = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);

        $title = $stmt->fetch(PDO::FETCH_ASSOC)['title'];

        if ($title !== null) {
            $note->id       = $id;
            $note->title    = $title;
        }

        return $note;
    }

    public function updateNote(Note $note): bool
    {
        $query  = "update Notes set title = :title where id = :id;";
        $stmt   = $this->connection->prepare($query);

        if ($stmt->execute([':id' => $note->id, ':title' => $note->title])) {
            return true;
        } else {
            return false;
        }
    }
}
