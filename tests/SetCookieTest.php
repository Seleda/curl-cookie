<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\SetCookie\SetCookieCurl;
use Seleda\CurlCookie\SetCookie\SetCookieGuzzle;
use Seleda\CurlCookie\SetCookie\SetCookieDb;

class SetCookieTest extends TestCase
{
    /**
     * @dataProvider providerCurlAndGuzzle
     */
    public function test_SetCookieCurlAndGuzzle($input, $name, $value, $expires, $maxAge, $domain, $path, $secure, $httpOnly, $SameSite)
    {
        $set_cookie = new SetCookieCurl($input);
        $this->assertEquals($name, $set_cookie->getName());
        $this->assertEquals($value, $set_cookie->getValue());
        $this->assertEquals($domain, $set_cookie->getDomain());
        $this->assertEquals($expires, $set_cookie->getExpires());
        $this->assertTrue((int)$maxAge === $set_cookie->getMaxAge());
        $this->assertEquals($path, $set_cookie->getPath());
        $this->assertTrue((bool)$secure === $set_cookie->getSecure());
        $this->assertTrue((bool)$httpOnly === $set_cookie->getHttpOnly());
        $this->assertEquals($SameSite, $set_cookie->getSameSite());
        $set_cookie = new SetCookieGuzzle(trim(substr($input, 12)));
        $this->assertEquals($name, $set_cookie->getName());
        $this->assertEquals($value, $set_cookie->getValue());
        $this->assertEquals($domain, $set_cookie->getDomain());
        $this->assertEquals($expires, $set_cookie->getExpires());
        $this->assertTrue((int)$maxAge === $set_cookie->getMaxAge());
        $this->assertEquals($path, $set_cookie->getPath());
        $this->assertTrue((bool)$secure === $set_cookie->getSecure());
        $this->assertTrue((bool)$httpOnly === $set_cookie->getHttpOnly());
        $this->assertEquals($SameSite, $set_cookie->getSameSite());
    }

    public function providerCurlAndGuzzle()
    {
        return [
            ['input' => 'Set-Cookie: 1P_JAR=2023-05-03-01; expires=Fri, 02-Jun-2023 01:38:25 GMT; path=/; domain=.google.com; Secure
', 'name' => '1P_JAR', 'value' => '2023-05-03-01', 'expires' => 'Fri, 02-Jun-2023 01:38:25 GMT', 'maxAge' => 1685669905, 'domain' => '.google.com', 'path' => '/', 'secure' => true, 'httpOnly' => false, 'sameSite' => ''],
            ['input' => 'Set-Cookie: AEC=AUEFqZe7cmgBcZpqkiCfjk_xR0uIqdKuif4DfNwfAhbQNDtnqThrha_NZA; expires=Mon, 30-Oct-2023 01:38:25 GMT; path=/; domain=.google.com; Secure; HttpOnly; SameSite=lax
', 'name' => 'AEC', 'value' => 'AUEFqZe7cmgBcZpqkiCfjk_xR0uIqdKuif4DfNwfAhbQNDtnqThrha_NZA', 'expires' => 'Mon, 30-Oct-2023 01:38:25 GMT', 'maxAge' => 1698629905, 'domain' => '.google.com', 'path' => '/', 'secure' => true, 'httpOnly' => true, 'sameSite' => 'lax'],
            ['input' => 'Set-Cookie: NID=511=CTaibhWD6UzrMDiTDXSikN12JXjODGBiMc1RC5B3nGS45tZvLiey0II0slDveTVQa0wNmKR3Hjnm0aitbeHAlCE804-RLk9apjWGwEnW7Ky3MdWT53WOYbMhWnjg1BxcLYAJHR_o9l-q6oDuYSFX2pI6wnC2aUJSb51JUhp5S5A; expires=Thu, 02-Nov-2023 01:38:25 GMT; path=/; domain=.google.com; HttpOnly
', 'name' => 'NID', 'value' => '511=CTaibhWD6UzrMDiTDXSikN12JXjODGBiMc1RC5B3nGS45tZvLiey0II0slDveTVQa0wNmKR3Hjnm0aitbeHAlCE804-RLk9apjWGwEnW7Ky3MdWT53WOYbMhWnjg1BxcLYAJHR_o9l-q6oDuYSFX2pI6wnC2aUJSb51JUhp5S5A', 'expires' => 'Thu, 02-Nov-2023 01:38:25 GMT', 'maxAge' => 1698889105, 'domain' => '.google.com', 'path' => '/', 'secure' => false, 'httpOnly' => true, 'sameSite' => '']
        ];
    }

    public function test_SetCookieDb()
    {
        $data = [
            [
                'input' => [
                    'name' => '1P_JAR',
                    'value' => '2023-05-03-01',
                    'expires' => 'Fri, 02-Jun-2023 01:38:25 GMT',
                    'maxAge' => '0',
                    'domain' => '.google.com',
                    'path' => '/',
                    'secure' => '1',
                    'httpOnly' => '0',
                    'sameSite' => ''
                ], 'name' => '1P_JAR', 'value' => '2023-05-03-01', 'expires' => 'Fri, 02-Jun-2023 01:38:25 GMT', 'maxAge' => 0, 'domain' => '.google.com', 'path' => '/', 'secure' => true, 'httpOnly' => false, 'sameSite' => ''
            ],
            [
                'input' => [
                    'name' => 'AEC',
                    'value' => 'AUEFqZe7cmgBcZpqkiCfjk_xR0uIqdKuif4DfNwfAhbQNDtnqThrha_NZA',
                    'expires' => 'Mon, 30-Oct-2023 01:38:25 GMT',
                    'maxAge' => '0',
                    'domain' => '.google.com',
                    'path' => '/',
                    'secure' => '1',
                    'httpOnly' => '1',
                    'sameSite' => 'lax'
                ], 'name' => 'AEC', 'value' => 'AUEFqZe7cmgBcZpqkiCfjk_xR0uIqdKuif4DfNwfAhbQNDtnqThrha_NZA', 'expires' => 'Mon, 30-Oct-2023 01:38:25 GMT', 'maxAge' => 0, 'domain' => '.google.com', 'path' => '/', 'secure' => true, 'httpOnly' => true, 'sameSite' => 'lax'
            ]
        ];

        foreach ($data as $val) {
            $set_cookie = new SetCookieDb($val['input']);
            $this->assertEquals($val['name'], $set_cookie->getName());
            $this->assertEquals($val['value'], $set_cookie->getValue());
            $this->assertEquals($val['domain'], $set_cookie->getDomain());
            $this->assertEquals($val['expires'], $set_cookie->getExpires());
            $this->assertTrue((int)$val['maxAge'] === $set_cookie->getMaxAge());
            $this->assertEquals($val['path'], $set_cookie->getPath());
            $this->assertTrue((bool)$val['secure'] === $set_cookie->getSecure());
            $this->assertTrue((bool)$val['httpOnly'] === $set_cookie->getHttpOnly());
            $this->assertEquals($val['sameSite'], $set_cookie->getSameSite());
        }
    }
}
