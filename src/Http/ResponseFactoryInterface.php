<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 18.07.18
 * Time: 10:40
 */

namespace App\Http;


use App\Entity\Note;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createNoteResponse(Note $note): ResponseInterface;
    public function createViolationListResponse(array $violationList): ResponseInterface;
}
