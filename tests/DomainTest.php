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
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
        $this->assertEquals('name=value;', $cookie->get('https://domain.com'));
    }

    public function test_simpleDomainWithDot()
    {
        $cookie = new Cookie([[
            'name' => 'name',
            'value' => 'value',
            'domain' => '.domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
        $this->assertEquals('name=value;', $cookie->get('https://domain.com'));
    }

    public function test_subDomain()
    {
        $cookie = new Cookie([[
            'name' => 'name',
            'value' => 'value',
            'domain' => '.domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
        $this->assertEquals('name=value;', $cookie->get('https://sub.domain.com'));
    }

    public function test_subDomainWithoutDot()
    {
        $cookie = new Cookie([[
            'name' => 'name',
            'value' => 'value',
            'domain' => 'domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]]);
        $this->assertEquals('', $cookie->get('https://sub.domain.com'));
    }
}
