<?php


namespace Seleda\CurlCookie\SetCookie;


class SetCookieCurl extends SetCookie
{

    protected function getLine(string $set_cookie):string
    {
        return substr(trim($set_cookie), 12);
    }
}