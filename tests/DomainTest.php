<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\Cookie;
use Seleda\CurlCookie\SetCookie\SetCookie;
use Seleda\CurlCookie\SetCookie\SetCookieDb;

class DomainTest extends TestCase
{
    public function test_simpleDomain()
    {
        $cookie = new Cookie([[
            'name' => 'name',
            'value' => 'value',
            'domain' => 'domain.com',
            'expires' => '',
            'maxAge' => 0,
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
        $this->assertEquals('name=value;', $cookie->get('https://domain.com'));
    }
}
