<?php

namespace App\Tests\Phake\Action;

use App\Action\GetNoteAction;
use App\Database\FinderInterface;
use App\Entity\Note;
use App\Http\Request;
use App\Http\ResponseFactoryInterface;
use App\Http\StringStream;
use App\Http\Uri;
use App\Validation\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GetNoteActionTest extends TestCase
{
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
    public function handleRequest_request_createNotFoundResponse()
    {
        $request    = $this->givenRequestForNotFoundResponse();
        $getNote    = $this->createGetNoteAction();

        $noteId     = '3';
        $this->givenFinder_findOneNote_returnsNull();
        $this->givenResponseFactory_createNotFoundResponse_returnsResponse();

        $getNote->handleRequest($request);

        $this->assertFinder_FindOneNote_isCalledOnceWithNoteId($noteId);
        $this->assertResponseFactory_createNotFoundResponse_isCalledOnceWithRequest($request);
    }

    /** @test */
    public function handleRequest_request_createNoteResponse()
    {
        $request        = $this->givenRequestForNoteResponse();
        $getNote        = $this->createGetNoteAction();

        $noteId         = '1';
        $statusCode     = 200;
        $note           = $this->givenFinder_findOneNote_returnsNote();
//        $note->id       = $noteId;
//        $note->title    = 'testPut';
        $this->givenResponseFactory_createNotFoundResponse_returnsResponse();

        $getNote->handleRequest($request);

        $this->assertFinder_FindOneNote_isCalledOnceWithNoteId($noteId);
        $this->assertResponseFactory_createNoteResponse_isCalledOnceWithRequest($request, $note, $statusCode);
    }

    private function givenRequestForNotFoundResponse(): Request
    {
        $uri    = new Uri('http://project.local/notes/3');
        $body   = new StringStream('');

        return new Request($uri, 'GET', '1.1', ['' => ['']], $body);
    }

    private function createGetNoteAction(): GetNoteAction
    {
        return new GetNoteAction($this->finder, $this->responseFactory, $this->validator);
    }

    private function givenFinder_findOneNote_returnsNull(): void
    {
        $note = null;

        \Phake::when($this->finder)
            ->findOneNote(\Phake::anyParameters())
            ->thenReturn($note);
    }

    private function givenResponseFactory_createNotFoundResponse_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createNotFoundResponse(\Phake::anyParameters())
            ->thenReturn($response);
    }

    private function assertFinder_FindOneNote_isCalledOnceWithNoteId($noteId): void
    {
        \Phake::verify($this->finder, \Phake::times(1))
            ->findOneNote($noteId);
    }

    private function assertResponseFactory_createNotFoundResponse_isCalledOnceWithRequest($request): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createNotFoundResponse($request);
    }
    private function givenRequestForNoteResponse(): Request
    {
        $uri    = new Uri('http://project.local/notes/1');
        $body   = new StringStream('');

        return new Request($uri, 'GET', '1.1', ['' => ['']], $body);
    }

    private function givenFinder_findOneNote_returnsNote(): Note
    {
        $note = \Phake::mock(Note::class);

        \Phake::when($this->finder)
            ->findOneNote(\Phake::anyParameters())
            ->thenReturn($note);

        return $note;
    }
    private function assertResponseFactory_createNoteResponse_isCalledOnceWithRequest($request, $note, $statusCode): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createNoteResponse($request, $note, $statusCode);
    }
}
