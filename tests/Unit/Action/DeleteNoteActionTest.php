<?php

namespace App\Tests\Unit\Action;

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
        $request        = $this->givenTestRequestForViolationList();
        $deleteNote     = $this->createDeleteNoteAction();

        $noteId         = $request->getUri()->getPath();
        $note           = $this->givenPersister_deleteNote_returnsNote();
        $violationList  = $this->givenValidator_ValidateFroNullNoteInDB_returnsViolationList();
        $this->givenResponseFactory_createViolationListResponse_returnsResponse();

        $deleteNote->handleRequest($request);

        $this->assertPersister_deleteNote_isCalledOnceWithNoteId($noteId);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList);
    }



    /** @test */
    public function handleRequest_request_createNoteResponse():void
    {
        $request    = $this->givenTestRequestForNoteResponse();
        $deleteNote = $this->createDeleteNoteAction();

        $noteId     = $request->getUri()->getPath();
        $statusCode = 200;
        $note       = $this->givenPersister_deleteNote_returnsNote();
        $this->givenValidator_ValidateForNullNoteInDB_returnsEmptyViolationList();
        $this->givenResponseFactory_createNoteResponse_returnsResponse();

        $deleteNote->handleRequest($request);

        $this->assertPersister_deleteNote_isCalledOnceWithNoteId($noteId);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createNoteResponse_isCalledOnceWithRequest($request,$note, $statusCode);
    }

    private function givenTestRequestForViolationList(): RequestInterface
    {
        $uri        = new Uri('http://project.local/notes/99');
        $body       = new StringStream('');
        $request    = new Request($uri,'DELETE','1.1',[''=>['']],$body);
        $resourceId = 99;

        return $request->withUri(new Uri($resourceId));
    }


    private function createDeleteNoteAction(): DeleteNoteAction
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

    private function givenResponseFactory_createViolationListResponse_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createViolationListResponse(\Phake::anyParameters())
            ->thenReturn($response);
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

    private function assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createViolationListResponse($request, $violationList);
    }

    private function givenTestRequestForNoteResponse(): RequestInterface
    {
        $uri        = new Uri('http://project.local/notes/1');
        $body       = new StringStream('');
        $request    = new Request($uri,'DELETE','1.1',[''=>['']],$body);
        $resourceId = '1';

        return $request->withUri(new Uri($resourceId));
    }

    private function givenValidator_ValidateForNullNoteInDB_returnsEmptyViolationList(): void
    {
        $violationList = [];

        \Phake::when($this->validator)
            ->validateForNullNoteInDB(\Phake::anyParameters())
            ->thenReturn($violationList);
    }

    private function givenResponseFactory_createNoteResponse_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createNoteResponse(\Phake::anyParameters())
            ->thenReturn($response);
    }

    private function assertResponseFactory_createNoteResponse_isCalledOnceWithRequest($request, $note, $statusCode): void
    {
        \Phake::verify($this->responseFactory,\Phake::times(1))
            ->createNoteResponse($request,$note,$statusCode);
    }
}
