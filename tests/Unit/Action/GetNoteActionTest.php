<?php

namespace App\Tests\Unit\Action;

use App\Action\GetNoteAction;
use App\Database\FinderInterface;
use App\Entity\Note;
use App\Http\Request;
use App\Http\ResponseFactoryInterface;
use App\Http\StringStream;
use App\Http\Uri;
use App\Validation\ValidatorInterface;
use App\Validation\Violation;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetNoteActionTest extends TestCase
{
    private const RESPONSE_STATUS_CODE = 200;

    /** @var FinderInterface */
    private $finder;

    /** @var ValidatorInterface */
    private $validator;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    protected function setUp(): void
    {
        $this->finder           = \Phake::mock(FinderInterface::class);
        $this->validator        = \Phake::mock(ValidatorInterface::class);
        $this->responseFactory  = \Phake::mock(ResponseFactoryInterface::class);
    }

    /** @test */
    public function handleRequest_request_createNotFoundResponse(): void
    {
        $request        = $this->givenRequestForNotFoundResponse();
        $getNote        = $this->createGetNoteAction();
        $noteId         = $request->getUri()->getPath();
        $note           = $this->givenFinder_findOneNote_returnsNote();
        $violationList  = $this->givenValidator_validateForNullNoteInDB_returnsViolationList();
        $this->givenResponseFactory_createViolationListResponse_returnsResponse();

        $getNote->handleRequest($request);

        $this->assertFinder_FindOneNote_isCalledOnceWithNoteId($noteId);
        $this->assertValidator_validateForNullNoteInDB_isCalledOnceWithNoteId($note);
        $this->assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList);
    }

    /** @test */
    public function handleRequest_request_createNoteResponse(): void
    {
        $request        = $this->givenRequestForNoteResponse();
        $getNote        = $this->createGetNoteAction();
        $noteId         = $request->getUri()->getPath();
        $note           = $this->givenFinder_findOneNote_returnsNote();
        $this->givenResponseFactory_createNoteResponse_returnsResponse();

        $getNote->handleRequest($request);

        $this->assertFinder_FindOneNote_isCalledOnceWithNoteId($noteId);
        $this->assertResponseFactory_createNoteResponse_isCalledOnceWithRequest($request, $note, self::RESPONSE_STATUS_CODE);
    }

    private function givenRequestForNotFoundResponse(): RequestInterface
    {
        $uri        = new Uri('http://project.local/notes/3');
        $body       = new StringStream('');
        $request    = new Request($uri, 'GET', '1.1', ['' => ['']], $body);
        $resourceId = '3';

        return $request->withUri(new Uri($resourceId));
    }

    private function createGetNoteAction(): GetNoteAction
    {
        return new GetNoteAction($this->finder, $this->responseFactory, $this->validator);
    }

    private function givenValidator_validateForNullNoteInDB_returnsViolationList(): array
    {
        $violationList = [new Violation("id","ID not found")];

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

    private function assertFinder_FindOneNote_isCalledOnceWithNoteId($noteId): void
    {
        \Phake::verify($this->finder, \Phake::times(1))
            ->findOneNote($noteId);
    }

    private function assertValidator_validateForNullNoteInDB_isCalledOnceWithNoteId($note): void
    {
        \Phake::verify($this->validator, \Phake::times(1))
            ->validateForNullNoteInDB($note);
    }

    private function assertResponseFactory_createViolationListResponse_isCalledOnceWithRequestAndViolationList($request, $violationList): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createViolationListResponse($request, $violationList);
    }

    private function givenRequestForNoteResponse(): RequestInterface
    {
        $uri        = new Uri('http://project.local/notes/1');
        $body       = new StringStream('');
        $request    = new Request($uri, 'GET', '1.1', ['' => ['']], $body);
        $resourceId = '1';

        return $request->withUri(new Uri($resourceId));
    }

    private function givenFinder_findOneNote_returnsNote(): Note
    {
        $note = \Phake::mock(Note::class);

        \Phake::when($this->finder)
            ->findOneNote(\Phake::anyParameters())
            ->thenReturn($note);

        return $note;
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
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createNoteResponse($request, $note, $statusCode);
    }
}
