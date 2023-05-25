<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\Cookie;
use Seleda\CurlCookie\SetCookie\SetCookieCurl;

class SecurePrefixTest extends TestCase
{
    public function setUp(): void
    {
        Cookie::reset();
    }

    public function test_cookiesAccepted()
    {
        $url_https = 'https://domain.ru';
        $url_http = 'http://domain.ru';
        $cookie = Cookie::getInstance();
        //Оба принимаются, когда исходят из защищенного источника (HTTPS)
        $inputs = [
            //'Set-Cookie: __Secure-ID=123; Secure; Domain=example.com',
            'Set-Cookie: __Secure-ID=123; Secure; Domain=domain.ru',
            'Set-Cookie: __Host-ID=123; Secure; Path=/'
        ];
        foreach ($inputs as $input) {
            $set_cookie = new SetCookieCurl($input);
            $cookie->addSetCookie($url_https, $set_cookie);
        }
        $this->assertEquals('__Secure-ID=123; __Host-ID=123;', $cookie->get($url_https));
    }

    public function test_cookiesNotAccepted()
    {
        $url_https = 'https://domain.ru';
        $url_http = 'http://domain.ru';
        $cookie = Cookie::getInstance();
        $inputs = [
            //'Set-Cookie: __Secure-ID=123; Secure; Domain=example.com',
            'Set-Cookie: __Secure-ID=123; Secure; Domain=domain.ru',
            'Set-Cookie: __Host-ID=123; Secure; Path=/'
        ];
        // не https
        foreach ($inputs as $input) {
            $set_cookie = new SetCookieCurl($input);
            $cookie->addSetCookie($url_http, $set_cookie);
        }
        $this->assertEquals('', $cookie->get($url_http));

        // Отклонено из-за отсутствия директивы Secure
        $input = 'Set-Cookie: __Secure-id=1';
        $set_cookie = new SetCookieCurl($input);
        $cookie->addSetCookie($url_https, $set_cookie);
        $this->assertEquals('', $cookie->get($url_https));
        // Отклонено из-за отсутствия директивы Path=/
        $input = 'Set-Cookie: __Host-id=1; Secure';
        $set_cookie = new SetCookieCurl($input);
        $cookie->addSetCookie($url_https, $set_cookie);
        $this->assertEquals('', $cookie->get($url_https));
        // Отклонено из-за установки домена
        $input = 'Set-Cookie: __Host-id=1; Secure; Path=/; domain=example.com';
        $set_cookie = new SetCookieCurl($input);
        $cookie->addSetCookie($url_https, $set_cookie);
        $this->assertEquals('', $cookie->get($url_https));


    }
}
