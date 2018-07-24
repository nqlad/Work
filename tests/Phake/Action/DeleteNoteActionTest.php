<?php

namespace App\Tests\Phake\Action;

use App\Action\DeleteNoteAction;
use App\Database\PersisterInterface;
use App\Entity\Note;
use App\Http\Request;
use App\Http\ResponseFactoryInterface;
use App\Http\StringStream;
use App\Http\Uri;
use App\Serialization\DeserializerInterface;
use App\Validation\ValidatorInterface;
use App\Validation\Violation;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DeleteNoteActionTest extends TestCase
{
    /** @var DeserializerInterface */
    private $deserialize;

    /** @var PersisterInterface */
    private $persister;

    /** @var ValidatorInterface */
    private $validator;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    protected function setUp(): void
    {
        $this->deserialize      = \Phake::mock(DeserializerInterface::class);
        $this->persister        = \Phake::mock(PersisterInterface::class);
        $this->validator        = \Phake::mock(ValidatorInterface::class);
        $this->responseFactory  = \Phake::mock(ResponseFactoryInterface::class);
    }

    /** @test */
    public function handleRequest_request_createViolationListResponse():void
    {
        $request        = $this->createTestRequestForViolationList();
        $deleteNote     = $this->createDeleteNote();

        $noteId         = 'notes';
        $note           = $this->givenPersister_deleteNote_returnsNote();
        $violationList  = $this->givenValidator_ValidateFroNullNoteInDB_returnsViolationList();
        $response       = $this->givenResponseFactory_createViolationListResponse_returnsResponse($request, $violationList);

        $deleteNote->handleRequest($request);

        $this->assertPersister_deleteNote_isCalledOnceWithNoteId($noteId);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList,$note);
    }



    private function createTestRequestForViolationList(): RequestInterface
    {
        $uri                    = new Uri('http://project.local/notes');
        $body                   = new StringStream('');

        return new Request($uri,'DELETE','1.1',[''=>['']],$body);
    }

    private function createDeleteNote(): DeleteNoteAction
    {
        return new DeleteNoteAction($this->deserialize, $this->persister, $this->responseFactory, $this->validator);
    }


    private function givenPersister_deleteNote_returnsNote(): Note
    {
        $note = \Phake::mock(Note::class);

        \Phake::when($this->persister)
            ->deleteNote(\Phake::anyParameters())
            ->thenReturn($note);

        return $note;
    }

    private function givenValidator_ValidateFroNullNoteInDB_returnsViolationList(): array
    {
        $violationList = [new Violation('id','ID not found')];

        \Phake::when($this->validator)
            ->validateForNullNoteInDB(\Phake::anyParameters())
            ->thenReturn($violationList);

        return $violationList;
    }

    private function givenResponseFactory_createViolationListResponse_returnsResponse($request, $violationList): ResponseInterface
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createViolationListResponse($request, $violationList)
            ->thenReturn($response);

        return $response;
    }

    private function assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList, $note): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createViolationListResponse($request, $violationList);
        \Phake::verify($this->responseFactory,\Phake::times(0))
            ->createNoteResponse($request,$note,200);
    }


    private function assertPersister_deleteNote_isCalledOnceWithNoteId($noteId): void
    {
        \Phake::verify($this->persister, \Phake::times(1))
            ->deleteNote($noteId);
    }

    private function assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note): void
    {
        \Phake::verify($this->validator, \Phake::times(1))
            ->validateForNullNoteInDB($note);
    }

    /** @test */
    public function handleRequest_request_createNoteResponse():void
    {
        $request    = $this->createTestRequestForViolationList();
        $deleteNote = $this->createDeleteNote();

        $noteId     = $this->givenDeleteNote_getNoteIdFromUri_returnsNoteId($request);
        $note       = $this->givenPersister_deleteNote_returnsNote($noteId);
        $this->givenValidator_ValidateForNullNoteInDB_returnsEmptyViolationList($note);
        $response   = $this->givenResponseFactory_createNoteResponse_returnsResponse($request,$note,200);

        $deleteNote->handleRequest($request);

        $this->assertDeleteNote_getNoteIdFromUri_isCalledOnceWithRequest($request);
        $this->assertPersister_deleteNote_isCalledOnceWithNoteId($noteId);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note);
    }

    private function givenValidator_ValidateForNullNoteInDB_returnsEmptyViolationList($note): void
    {
        $violationList = [''];

        \Phake::when($this->validator)
            ->ValidateForNullNoteInDB($note)
            ->thenReturn(null);
    }

    private function givenResponseFactory_createNoteResponse_returnsResponse($request, $note, $statusCode): ResponseInterface
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createNoteResponse($request, $note, $statusCode)
            ->thenReturn($response);

        return $response;
    }

    private function assertResponseFactory_createNoteResponse_isCalledOnceWithRequest($request, $violationList, $note): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createViolationListResponse($request, $violationList);
        \Phake::verify($this->responseFactory,\Phake::times(0))
            ->createNoteResponse($request,$note,200);
    }
}
