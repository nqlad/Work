<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 10.07.18
 * Time: 12:29
 */

namespace App;


use App\Http\RequestFactory;
use App\Http\RequestHandler;
use App\Http\ResponseSender;


class Kernel
{
    /** @var RequestFactory */
    private $requestFactory;
    /** @var RequestHandler */
    private $requestHandler;
    /** @var ResponseSender */
    private $responseSender;

    public function __construct()
    {
        $requestFactory = new RequestFactory();
        $requestHandler = new RequestHandler();
        $responseSender = new ResponseSender();

        $this->requestFactory = $requestFactory;
        $this->requestHandler = $requestHandler;
        $this->responseSender = $responseSender;
    }

    public function run(): void
    {
        $request = $this->requestFactory->createRequest();
        $response = $this->requestHandler->handlerRequest($request);
        $this->responseSender->sendResponse($response);
    }
}