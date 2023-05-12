<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\Cookie;
use Seleda\CurlCookie\SetCookie\SetCookieCurl;

class RemoveTest extends TestCase
{
    public function test_removeByExpires()
    {
        $cookie = Cookie::getInstance([[
            'name' => 'name',
            'value' => 'value',
            'domain' => 'domain.com',
            'expires' => 'Fri, 20-Oct-2023 01:13:20 GMT',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
        $set_cookie = new SetCookieCurl('Set-Cookie: name=value; expires=Fri, 02-Jun-2023 01:38:25 GMT; path=/; domain=domain.com; Secure
');
        $cookie->addSetCookie('https://domain.com', $set_cookie);
        $this->assertEquals('', $cookie->get('https://domain.com/'));
    }

    public function test_removeByMaxAge()
    {
        $cookie = Cookie::getInstance([[
            'name' => 'name',
            'value' => 'value',
            'domain' => 'domain.com',
            'expires' => 'Fri, 20-Oct-2023 01:13:20 GMT',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
    }
}
