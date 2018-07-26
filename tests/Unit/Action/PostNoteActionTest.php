<?php

namespace App\Tests\Unit\Action;

use App\Action\PostNoteAction;
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

class PostNoteActionTest extends TestCase
{
    /** @var DeserializerInterface */
    private $deserializer;

    /** @var ValidatorInterface */
    private $validator;

    /** @var PersisterInterface */
    private $persister;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    protected function setUp(): void
    {
        $this->deserializer     = \Phake::mock(DeserializerInterface::class);
        $this->validator        = \Phake::mock(ValidatorInterface::class);
        $this->persister        = \Phake::mock(PersisterInterface::class);
        $this->responseFactory  = \Phake::mock(ResponseFactoryInterface::class);
    }

    /** @test */
    public function handleRequest_request_createViolationListResponse(): void
    {
        $request        = $this->givenRequestForCreateViolationListResponse();
        $postNote       = $this->createPostNoteAction();

        $requestBody    = $request->getBody();
        $note           = $this->givenDeserializer_deserialize_returnsNote();
        $violationList  = $this->givenValidator_validate_returnsViolationList();
        $this->givenResponseFactory_createViolationListResponse_returnsResponse();

        $postNote->handleRequest($request);

        $this->assertDeserializer_deserialize_isCalledOnceWithRequestBody($requestBody);
        $this->assertValidator_validate_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList);
    }

    /** @test */
    public function handleRequest_request_createNoteResponse():void
    {
        $request        = $this->givenRequestForCreateNoteResponse();
        $postNote       = $this->createPostNoteAction();

        $statusCode     = 200;
        $requestBody    = $request->getBody();
        $note           = $this->givenDeserializer_deserialize_returnsNote();
        $this->givenValidator_validate_returnsEmptyViolationList();
        $persistNote    = $this->givenPersister_persist_returnsPersistNote();
        $this->givenResponseFactory_createNoteResponse_returnsResponse();

        $postNote->handleRequest($request);

        $this->assertDeserializer_deserialize_isCalledOnceWithRequestBody($requestBody);
        $this->assertValidator_validate_isCalledOnceWithNote($note);
        $this->assertPersister_persist_isCalledOnceWithNote($note);
        $this->assertResponseFactory_createNoteResponse_isCalledOnceWithRequestAndPersistNoteAndStatusCode($request, $persistNote, $statusCode);
    }

    private function givenRequestForCreateViolationListResponse(): RequestInterface
    {
        $uri    = new Uri('http://project.local/notes');
        $note   = new Note();
        $note->title = 't';
        $body   = new StringStream(json_encode($note));

        return new Request($uri, 'POST', '1.1', ['' => ['']], $body);
    }

    private function createPostNoteAction(): PostNoteAction
    {
        return  new PostNoteAction($this->deserializer, $this->persister, $this->responseFactory, $this->validator);
    }

    private function givenDeserializer_deserialize_returnsNote(): Note
    {
        $note = \Phake::mock(Note::class);

        \Phake::when($this->deserializer)
            ->deserialize(\Phake::anyParameters())
            ->thenReturn($note);

        return $note;
    }

    private function givenValidator_validate_returnsViolationList(): array
    {
        $violationList = [new Violation('title','Length must be more than one symbol')];

        \Phake::when($this->validator)
            ->validate(\Phake::anyParameters())
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

    private function assertDeserializer_deserialize_isCalledOnceWithRequestBody($requestBody): void
    {
        \Phake::verify($this->deserializer, \Phake::times(1))
            ->deserialize($requestBody);
    }

    private function assertValidator_validate_isCalledOnceWithNote($note): void
    {
        \Phake::verify($this->validator, \Phake::times(1))
            ->validate($note);
    }

    private function assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createViolationListResponse($request, $violationList);
    }

    private function givenRequestForCreateNoteResponse(): RequestInterface
    {
        $uri    = new Uri('http://project.local/notes');
        $note   = new Note();
        $note->title = 'title';
        $body   = new StringStream(json_encode($note));

        return new Request($uri, 'POST', '1.1', ['' => ['']], $body);
    }

    private function givenValidator_validate_returnsEmptyViolationList(): void
    {
        $violationList = [];

        \Phake::when($this->validator)
        ->validate(\Phake::anyParameters())
        ->thenReturn($violationList);
    }

    private function givenPersister_persist_returnsPersistNote(): Note
    {
        $persistNote = \Phake::mock(Note::class);

        \Phake::when($this->persister)
            ->persist(\Phake::anyParameters())
            ->thenReturn($persistNote);

        return $persistNote;
    }

    private function givenResponseFactory_createNoteResponse_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createNoteResponse(\Phake::anyParameters())
            ->thenReturn($response);
    }

    private function assertPersister_persist_isCalledOnceWithNote($note): void
    {
        \Phake::verify($this->persister, \Phake::times(1))
            ->persist($note);
    }

    private function assertResponseFactory_createNoteResponse_isCalledOnceWithRequestAndPersistNoteAndStatusCode($request, $persistNote, $statusCode): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createNoteResponse($request, $persistNote, $statusCode);
    }
}
