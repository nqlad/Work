<?php

namespace App\Http;


use App\Model\Request;
use App\Model\Response;

class RequestHandler implements RequestHandlerInterface
{
    private $action;

    private $noteId;
    /**
     * @param Request $request
     * @return Response
     */
    public function handlerRequest($request)
    {
        $data = new QueryToData();

        $urls = $request->getUri();
        if ($urls[1] == null) {
            $this->action = 'readAll';
        }else{
            $this->action = 'readById';
            $this->noteId = $urls[1];
        }
        $notes = $data->createData($this->action,$this->noteId);

        //$controller = new Controller();
        //$body       = $controller->requestGet($notes, $request->getUri());

        $response   = new Response($notes);
        return $response;
    }
}
