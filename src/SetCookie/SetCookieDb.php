<?php


namespace Seleda\CurlCookie\SetCookie;

use Seleda\CurlCookie\Cookie;

class SetCookieDb extends SetCookie
{
    public function __construct(array $set_cookie)
    {
        $this->name = $set_cookie['name'];
        $this->value = $set_cookie['value'];
        $this->expires = $set_cookie['expires'];
        $this->maxAge = $set_cookie['maxAge'];
        $this->domain = $set_cookie['domain'];
        $this->path = Cookie::fixPath($set_cookie['path']);
        $this->secure = $set_cookie['secure'];
        $this->httpOnly = $set_cookie['httpOnly'];
        $this->sameSite = $set_cookie['sameSite'];

    }

    protected function getLine(string $set_cookie): string
    {
        // TODO: Implement getLine() method.
    }
}