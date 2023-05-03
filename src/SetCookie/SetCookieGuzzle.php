<?php


namespace Seleda\CurlCookie\SetCookie;


class SetCookieGuzzle extends SetCookie
{

    protected function getLine(string $set_cookie):string
    {
        return $set_cookie;
    }
}