<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\Cookie;
use Seleda\CurlCookie\SetCookie\SetCookieCurl;
use Seleda\CurlCookie\SetCookie\SetCookieGuzzle;

class CookieTest extends TestCase
{
    public function test_empty()
    {
        $cookie = new Cookie();
        $this->assertEquals('', $cookie->get('http://empty.cookies'));
    }

    public function test_setCookiesCurl()
    {
        $url = 'https://google.com';
        $exp = '1P_JAR=2023-04-23-01; AEC=AUEFqZeFcO0zx25Hq5YPuOd_yqPQKdSmc-CzqnUIO2E9v61vlapmtU4DgGc; NID=511=c-AuIuh59Rz6XL_7KdQjdI7X7uRLKP_1YXT_lBIDt69mY2DMGHXd_G8jBkNUZL-YacFH9tcVTExaEAnO2ZpznAStH9mo9GNQZOtlkjHDozbpXMYvt0fVB8nbUVVaf-nqFKNbNt6abwX0cG0kxPZS356F8V_-pYCzwx-y4bcq9zE;';
        $headers = json_decode(file_get_contents(dirname(__FILE__) . '/headers.json'), true);
        $cookie = new Cookie();
        foreach ($headers['Set-Cookie'] as $val) {
            $set_cookie = new SetCookieGuzzle($val);
            $cookie->addSetCookie($set_cookie);
        }

        $cookies = $cookie->get($url);
        $this->assertEquals($exp, $cookies);
    }

    public function test_subDomain()
    {
        $url = 'https://subdomain.domain.com/path';
        $cookie = new Cookie([[
            'name' => 'name',
            'value' => 'value',
            'expires' => '',
            'maxAge' => 0,
            'domain' => '.domain.com',
            'path' => '/path',
            'secure' => 0,
            'httpOnly' => 0,
            'sameSite' => ''
        ]]);
        $cookies = $cookie->get($url);

        $this->assertEquals('name=value;', $cookies);
    }
}