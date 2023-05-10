<?php


namespace Seleda\CurlCookie;

use Seleda\CurlCookie\SetCookie\SetCookie;
use Seleda\CurlCookie\SetCookie\SetCookieDb;

class Cookie
{
    private array $cookies = [];

    public function __construct(array $cookies_db = [])
    {
        foreach ($cookies_db as $row) {
            $set_cookie = new SetCookieDb($row);
            $this->addSetCookie($set_cookie);
        }
    }

    public function addSetCookie(SetCookie $set_cookie):void
    {
        if (empty($this->cookies[$set_cookie->getDomain()])) {
            $this->cookies[$set_cookie->getDomain()] = [];
        }
        if (empty($this->cookies[$set_cookie->getDomain()][$set_cookie->getPath()])) {
            $this->cookies[$set_cookie->getDomain()][$set_cookie->getPath()] = [];
        }
        $this->cookies[$set_cookie->getDomain()][$set_cookie->getPath()][] = $set_cookie;
    }

    public function get(string $url): string
    {
        $parse = self::parseUrl($url);

        $domain = $parse['host'];
        $path = $parse['path'];

        $set_cookies = [];
        if (isset($this->cookies[$domain][$path])) {
            $set_cookies = array_merge($set_cookies, $this->cookies[$domain][$path]);
        }
        if (isset($this->cookies['.'.$domain][$path])) {
            $set_cookies = array_merge($set_cookies, $this->cookies['.'.$domain][$path]);
        }
        if (substr_count($domain, '.') == 2) {
            preg_match('/^.+(\.[^\.]+\.[^\.]+)$/', $domain, $matches);
            $domain = $matches[1];
            if (isset($this->cookies[$domain][$path])) {
                $set_cookies = array_merge($set_cookies, $this->cookies[$domain][$path]);
            }
        }

        $cookies = '';
        foreach ($set_cookies as $set_cookie) {
            if ($parse['scheme'] == 'http' && $set_cookie->getSequire()) {
                 continue;
            }
            $cookies .= (strlen($cookies) > 0 ? ' ' : '') . $set_cookie->getName().'='.$set_cookie->getValue().';';
        }

        return $cookies;
    }

    public static function fixPath(string $path = ''): string
    {
        if (empty($path)) {
            return '/';
        }
        return $path;
    }

    public static function parseUrl($url)
    {
        $parse = parse_url($url);
        if (empty($parse['path'])) {
            $parse['path'] = '/';
        }
        $parse['path'] = self::fixPath($parse['path']);
        return $parse;
    }
}