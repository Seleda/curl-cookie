<?php


use PHPUnit\Framework\TestCase;
use Seleda\CurlCookie\Cookie;

class CookieTest extends TestCase
{
    public function _test_empty()
    {
        $this->assertEquals('', Cookie::get('http://empty.cookies'));
    }

    public function _test_setCookies()
    {
        $url = 'https://google.com';
        $exp = '1P_JAR=2023-04-23-01; AEC=AUEFqZeFcO0zx25Hq5YPuOd_yqPQKdSmc-CzqnUIO2E9v61vlapmtU4DgGc; NID=511=c-AuIuh59Rz6XL_7KdQjdI7X7uRLKP_1YXT_lBIDt69mY2DMGHXd_G8jBkNUZL-YacFH9TtcVExaEAnO2ZpznAStH9mo9GNQZOtlkjHDozbpXMYvt0fVB8nbUVVaf-nqFKNbNt6abwX0cG0kxPZS356F8V_-pYCzwx-y4bcq9zE;';
        $headers = json_decode(require_once dirname(__FILE__) . '/headers.json', true);
        Cookie::set($headers['Set-Cookie']);
        $cookies = Cookie::get($url);
        $this->assertEquals($exp, $cookies);
    }

    private static function curlResponseHeaderCallback($ch, $headerLine)
    {
        //var_dump($headerLine);
        return strlen($headerLine);
    }

    public function test_getCookie()
    {
        $cookies = [];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://google.com',
            CURLOPT_HEADERFUNCTION => function($curl, $head_line) use (&$cookies) {
                if (strpos($head_line, 'Set-Cookie') !== false) {
                    $cookies[] = $head_line;
                }
                return strlen($head_line);
            },
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        var_dump($cookies);
    }
}