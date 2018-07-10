<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 10.07.18
 * Time: 12:34
 */

namespace App\Http;


interface RequestHandlerInterface
{
    public function handlerRequest($request);
}