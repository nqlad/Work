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
        $query      = "insert into notes values(default, :title) returning id";
        $statement  = $this->connection->prepare($query);
        $statement->execute([':title' => $note->title]);

        $note->id   = $statement->fetch(PDO::FETCH_ASSOC)['id'];

        return $note;
    }

    public function findNoteCollection(): ?array
    {
        $query      = "select id, title from notes;";
        $statement  = $this->connection->prepare($query);
        $statement->execute();

        $notes      = $statement->fetchAll(PDO::FETCH_KEY_PAIR);

        return $notes;
    }

    public function findOneNote(string $id): Note
    {
        $query      = "select * from notes where id = :id;";
        $statement  = $this->connection->prepare($query);
        $statement->setFetchMode(PDO::FETCH_CLASS, Note::class);
        $statement->execute([':id' => $id]);

        $note       = $statement->fetch(PDO::FETCH_CLASS);

        if(!$note){
            $note           = new Note();
            $note->id       = null;
            $note->title    = null;
        }

        return $note;
    }

    public function deleteNote(string $id): Note
    {
        $note       = new Note();

        $query      = "delete from notes where id = :id returning title;";
        $statement  = $this->connection->prepare($query);
        $statement->execute([':id' => $id]);

        $title      = $statement->fetch(PDO::FETCH_ASSOC)['title'];

        if ($title !== null) {
            $note->id       = $id;
            $note->title    = $title;
        }

        return $note;
    }

    public function updateNote(Note $note): bool
    {
        $query      = "update notes set title = :title where id = :id;";
        $statement  = $this->connection->prepare($query);
        $statement->execute([':id' => $note->id, ':title' => $note->title]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            $note->id = null;
            return false;
        }
    }
}
