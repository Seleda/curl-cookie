<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\Cookie;
use Seleda\CurlCookie\SetCookie\SetCookie;
use Seleda\CurlCookie\SetCookie\SetCookieDb;

class DomainTest extends TestCase
{
    public function setUp(): void
    {
        Cookie::reset();
    }

    public function test_simpleDomain()
    {
        $cookie = Cookie::getInstance();
        $cookie->addSetCookie('https://domain.com', new SetCookieDb([
            'name' => 'name',
            'value' => 'value',
            'domain' => 'domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]));
        $this->assertEquals('name=value;', $cookie->get('https://domain.com'));
        $this->assertEquals('name=value;', $cookie->get('https://sub.domain.com'));
    }

    public function test_simpleDomainWithDot()
    {
        $cookie = Cookie::getInstance();
        $cookie->addSetCookie('https://domain.com/', new SetCookieDb([
            'name' => 'name',
            'value' => 'value',
            'domain' => '.domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]));
        $this->assertEquals('name=value;', $cookie->get('https://domain.com'));
    }

    public function test_subDomain()
    {
        $cookie = Cookie::getInstance();
        $cookie->addSetCookie('https://domain.com', new SetCookieDb([
            'name' => 'name',
            'value' => 'value',
            'domain' => '.domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]));
        $this->assertEquals('name=value;', $cookie->get('https://sub.domain.com'));
    }

    public function test_subDomainWithoutDot()
    {
        $cookie = Cookie::getInstance();
        $cookie->addSetCookie('https://domain.com', new SetCookieDb([
            'name' => 'name',
            'value' => 'value',
            'domain' => 'domain.com',
            'expires' => '',
            'path' => '',
            'secure' => true,
            'httpOnly' => true,
            'sameSite' => ''
        ]));
        $this->assertEquals('', $cookie->get('https://sub.domain.com'));
    }
}
