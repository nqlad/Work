<?php

namespace App\Tests\Unit\Action;

use App\Action\UpdateNoteAction;
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

class UpdateNoteActionTest extends TestCase
{
    /** @var DeserializerInterface */
    private $deserialize;

    /** @var ValidatorInterface */
    private $validator;

    /** @var PersisterInterface */
    private $persister;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    protected function setUp(): void
    {
        $this->deserialize      = \Phake::mock(DeserializerInterface::class);
        $this->validator        = \Phake::mock(ValidatorInterface::class);
        $this->persister        = \Phake::mock(PersisterInterface::class);
        $this->responseFactory  = \Phake::mock(ResponseFactoryInterface::class);
    }

    /** @test */
    public function handleRequest_request_createViolationListResponse(): void
    {
        $request        = $this->givenRequestForCreateViolationListResponse();
        $updateNote     = $this->createUpdateNoteAction();

        $requestBody    = $request->getBody();
        $note           = $this->givenDeserialize_deserialize_returnsNote();

        $note->id       = 1;

        $violationList  = $this->givenValidator_validate_returnsViolationList();
        $violationList  += $this->givenValidator_validateForNullIdInDB_returnsViolationList();
        $this->givenPersister_updateNote_returnsFalse();
        $this->givenResponseFactory_createViolationListResponse_returnsResponse();

        $updateNote->handleRequest($request);

        $this->assertDeserialize_deserialize_isCalledOnceWithRequestBody($requestBody);
        $this->assertValidator_validate_isCalledOnceWithNote($note);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note);
        $this->assertPersister_updateNote_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList);
    }

    /** @test */
    public function handleRequest_request_createNoteResponse():void
    {
        $request        = $this->givenRequestForCreateNoteResponse();
        $updateNote     = $this->createUpdateNoteAction();

        $requestBody    = $request->getBody();
        $note           = $this->givenDeserialize_deserialize_returnsNote();

        $note->id       = 1;
        $statusCode     = 200;

        $this->givenValidator_validate_returnsEmptyViolationList();
        $this->givenValidator_validateForNullIdInDB_returnsEmptyViolationList();
        $this->givenPersister_updateNote_returnsTrue();
        $this->givenResponseFactory_createNoteResponse_returnsResponse();

        $updateNote->handleRequest($request);

        $this->assertDeserialize_deserialize_isCalledOnceWithRequestBody($requestBody);
        $this->assertValidator_validate_isCalledOnceWithNote($note);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note);
        $this->assertPersister_updateNote_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createNoteResponse_isCalledOnceWithRequestAndNoteAndStatusCode($request, $note, $statusCode);
    }

    private function givenRequestForCreateViolationListResponse(): RequestInterface
    {
        $uri    = new Uri('http://project.local/notes/1');
        $note   = new Note();
        $note->title = 't';
        $body   = new StringStream(json_encode($note));

        return new Request($uri, 'PUT', '1.1', ['' => ['']], $body);
    }

    private function createUpdateNoteAction(): UpdateNoteAction
    {
        return new UpdateNoteAction($this->deserialize, $this->validator, $this->persister, $this->responseFactory);
    }

    private function givenDeserialize_deserialize_returnsNote(): Note
    {
        $note = \Phake::mock(Note::class);

        \Phake::when($this->deserialize)
            ->deserialize(\Phake::anyParameters())
            ->thenReturn($note);

        return $note;
    }

    private function givenValidator_validate_returnsViolationList(): array
    {
        $violationList = [new Violation('title', 'Length must be more than one symbol')];

        \Phake::when($this->validator)
            ->validate(\Phake::anyParameters())
            ->thenReturn($violationList);

        return $violationList;
    }

    private function givenValidator_validateForNullIdInDB_returnsViolationList(): array
    {
        $violationList = [];

        \Phake::when($this->validator)
            ->validateForNullNoteInDB(\Phake::anyParameters())
            ->thenReturn($violationList);

        return $violationList;
    }

    private function givenPersister_updateNote_returnsFalse(): void
    {
        \Phake::when($this->persister)
            ->updateNote(\Phake::anyParameters())
            ->thenReturn(false);
    }

    private function givenResponseFactory_createViolationListResponse_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createViolationListResponse(\Phake::anyParameters())
            ->thenReturn($response);
    }

    private function assertDeserialize_deserialize_isCalledOnceWithRequestBody($requestBody): void
    {
        \Phake::verify($this->deserialize, \Phake::times(1))
            ->deserialize($requestBody);
    }

    private function assertValidator_validate_isCalledOnceWithNote($note): void
    {
        \Phake::verify($this->validator, \Phake::times(1))
            ->validate($note);
    }

    private function assertValidator_validateForNullNoteInDB_isCalledOnceWithNote($note): void
    {
        \Phake::verify($this->validator, \Phake::times(1))
            ->validateForNullNoteInDB($note);
    }

    private function assertPersister_updateNote_isCalledOnceWithNote($note): void
    {
        \Phake::verify($this->persister, \Phake::times(1))
            ->updateNote($note);
    }
    private function assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createViolationListResponse($request, $violationList);
    }

    private function givenRequestForCreateNoteResponse(): RequestInterface
    {
        $uri    = new Uri('http://project.local/notes/1');
        $note   = new Note();
        $note->title = 'update';
        $body   = new StringStream(json_encode($note));

        return new Request($uri, 'PUT', '1.1', ['' => ['']], $body);
    }

    private function givenValidator_validate_returnsEmptyViolationList(): void
    {
        $violationList = [];

        \Phake::when($this->validator)
            ->validate(\Phake::anyParameters())
            ->thenReturn($violationList);
    }

    private function givenValidator_validateForNullIdInDB_returnsEmptyViolationList(): void
    {
        $violationList = [];

        \Phake::when($this->validator)
            ->validateForNullNoteInDB(\Phake::anyParameters())
            ->thenReturn($violationList);
    }

    private function givenPersister_updateNote_returnsTrue(): void
    {
        \Phake::when($this->persister)
            ->updateNote(\Phake::anyParameters())
            ->thenReturn(true);
    }

    private function givenResponseFactory_createNoteResponse_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createNoteResponse(\Phake::anyParameters())
            ->thenReturn($response);
    }

    private function assertResponseFactory_createNoteResponse_isCalledOnceWithRequestAndNoteAndStatusCode($request, $note, $statusCode): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createNoteResponse($request, $note, $statusCode);
    }
}
