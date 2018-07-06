<?php
/**
 * Created by PhpStorm.
 * User: heify
 * Date: 05.07.18
 * Time: 16:46
 */

class Database
{
    private $host = 'localhost';
    private $port = 5432;
    private $dbname = 'postgres';
    private $user = 'postgres';
    private $pass = 'yfNL4W';
    private $connect;

    /**
     * @return null|PDO
     */
    public function getConnection()
    {
        $this->connect = null;

        try{
            $this->connect = new PDO("pgsql:host=". $this->host . ";port=". $this->port . ";dbname=". $this->dbname, $this->user, $this->pass);
        }catch (PDOException $e){
            echo "Connection error: " . $e->getMessage();
        }

        return $this->connect;
    }
}
