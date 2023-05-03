<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\SetCookie\SetCookieCurl;
use Seleda\CurlCookie\SetCookie\SetCookieGuzzle;

class SetCookieTest extends TestCase
{
    public function test_getSameSite()
    {
        $data = [
            'Set-Cookie: 1P_JAR=2023-05-03-01; expires=Fri, 02-Jun-2023 01:38:25 GMT; path=/; domain=.google.com; Secure
',
            'Set-Cookie: AEC=AUEFqZe7cmgBcZpqkiCfjk_xR0uIqdKuif4DfNwfAhbQNDtnqThrha_NZA; expires=Mon, 30-Oct-2023 01:38:25 GMT; path=/; domain=.google.com; Secure; HttpOnly; SameSite=lax
',
            'Set-Cookie: NID=511=CTaibhWD6UzrMDiTDXSikN12JXjODGBiMc1RC5B3nGS45tZvLiey0II0slDveTVQa0wNmKR3Hjnm0aitbeHAlCE804-RLk9apjWGwEnW7Ky3MdWT53WOYbMhWnjg1BxcLYAJHR_o9l-q6oDuYSFX2pI6wnC2aUJSb51JUhp5S5A; expires=Thu, 02-Nov-2023 01:38:25 GMT; path=/; domain=.google.com; HttpOnly
'
        ];
        $exp = [
            '1P_JAR=2023-05-03-01; expires=Fri, 02-Jun-2023 01:38:25 GMT; path=/; domain=.google.com; Secure',
            'AEC=AUEFqZe7cmgBcZpqkiCfjk_xR0uIqdKuif4DfNwfAhbQNDtnqThrha_NZA; expires=Mon, 30-Oct-2023 01:38:25 GMT; path=/; domain=.google.com; Secure; HttpOnly; SameSite=lax',
            'NID=511=CTaibhWD6UzrMDiTDXSikN12JXjODGBiMc1RC5B3nGS45tZvLiey0II0slDveTVQa0wNmKR3Hjnm0aitbeHAlCE804-RLk9apjWGwEnW7Ky3MdWT53WOYbMhWnjg1BxcLYAJHR_o9l-q6oDuYSFX2pI6wnC2aUJSb51JUhp5S5A; expires=Thu, 02-Nov-2023 01:38:25 GMT; path=/; domain=.google.com; HttpOnly'
        ];
        $set_cookie = new SetCookieCurl($data[0]);
        $this->assertEquals('1P_JAR', $set_cookie->getName());
        $this->assertEquals('2023-05-03-01', $set_cookie->getValue());
        $this->assertEquals('.google.com', $set_cookie->getDomain());
        $this->assertEquals('Fri, 02-Jun-2023 01:38:25 GMT', $set_cookie->getExpires());
        $this->assertEquals(0, $set_cookie->getMaxAge());
        $this->assertEquals('/', $set_cookie->getPath());
        $this->assertEquals(true, $set_cookie->getSecure());
        $this->assertEquals(false, $set_cookie->getHttpOnly());
        $this->assertEquals('', $set_cookie->getSameSite());
    }
}
