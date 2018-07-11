<?php

namespace App\Controller;

use PDO;

class Controller
{
//    private $method;
//
//    public function __construct($mtd)
//    {
//        $this->method = $mtd;
//    }

    /**
     * @param $notes
     * @param $urls
     * @return array|null
     */
    public function requestGet($notes, $urls): array
    {
        //"message" => "No notes found."
        $countNotes = $notes->rowCount();
        if ($countNotes <= 0) {
            $response = [];
        }
//todo разделить
        if ($urls[1] == null) {
            $response = $notes->fetchAll(PDO::FETCH_KEY_PAIR);
        }

        return $response;
    }
}
