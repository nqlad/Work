<?php

namespace App\Tests\Unit\Action;

use App\Action\GetNoteCollectionAction;
use App\Database\FinderInterface;
use App\Http\Request;
use App\Http\ResponseFactoryInterface;
use App\Http\StringStream;
use App\Http\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetAllNoteActionTest extends TestCase
{
    private const RESPONSE_STATUS_CODE = 200;

    /** @var FinderInterface */
    private $finder;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    protected function setUp():void
    {
        $this->finder           = \Phake::mock(FinderInterface::class);
        $this->responseFactory  = \Phake::mock(ResponseFactoryInterface::class);
    }

    /** @test */
    public function handleRequest_request_createNoteCollectionWith200StatusCode(): void
    {
        $request        = $this->givenRequestForGetAllNoteAction();
        $getAllNote     = $this->createGetAllNoteAction();
        $noteCollection = $this->givenFinder_findAllNote_returnsNoteCollection();
        $this->givenResponseFactory_createNoteCollection_returnsResponse();

        $getAllNote->handleRequest($request);

        $this->assertFinder_findAllNote_isCalledOnce();
        $this->assertResponseFactory_creareNoteCollection_isCalledOnceWithRequestAndNoteCollectionAndStatusCode($request, $noteCollection, self::RESPONSE_STATUS_CODE);
    }

    private function givenRequestForGetAllNoteAction(): RequestInterface
    {
        $uri    = new Uri('http://project.local/notes');
        $body   = new StringStream('');

        return new Request($uri, 'GET', '1.1', ['' => ['']], $body);
    }

    private function createGetAllNoteAction(): GetNoteCollectionAction
    {
        return new GetNoteCollectionAction($this->finder, $this->responseFactory);
    }

    private function givenFinder_findAllNote_returnsNoteCollection(): array
    {
        $noteCollection = [2 => 'testPost', 1 => 'testPut'];

        \Phake::when($this->finder)
            ->findNoteCollection()
            ->thenReturn($noteCollection);

        return $noteCollection;
    }

    private function givenResponseFactory_createNoteCollection_returnsResponse(): void
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->responseFactory)
            ->createNoteCollection(\Phake::anyParameters())
            ->thenReturn($response);
    }

    private function assertFinder_findAllNote_isCalledOnce(): void
    {
        \Phake::verify($this->finder, \Phake::times(1))
            ->findNoteCollection();
    }

    private function assertResponseFactory_creareNoteCollection_isCalledOnceWithRequestAndNoteCollectionAndStatusCode($request, $noteCollection, $statusCode): void
    {
        \Phake::verify($this->responseFactory, \Phake::times(1))
            ->createNoteCollection($request, $noteCollection, $statusCode);
    }
}
