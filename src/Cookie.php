<?php


namespace Seleda\CurlCookie;

use Seleda\CurlCookie\SetCookie\SetCookie;
use Seleda\CurlCookie\SetCookie\SetCookieDb;

class Cookie
{
    private static $instance;

    private array $cookies = [];

    private function __construct(array $cookies_db = [])
    {
        foreach ($cookies_db as $row) {
            $set_cookie = new SetCookieDb($row);
            $this->addSetCookie($set_cookie);
        }
    }

    public static function getInstance():self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addSetCookie(string $url, SetCookie $set_cookie):bool
    {
        $parse = self::parseUrl($url);
        $scheme = $parse['scheme'];
        $domain = $parse['host'];
        if (empty($this->cookies[$domain])) {
            $this->cookies[$domain] = [];
        }
        if (!$set_cookie->getDomain()) {
            $set_cookie->setDomain($domain);
        }
        if (strpos($set_cookie->getName(), '__Secure-') === 0 && !$set_cookie->getSecure()) {
            return false;
        }
        // TODO __Host-
        if (empty($this->cookies[$set_cookie->getDomain()])) {
            $this->cookies[$set_cookie->getDomain()] = [];
        }
        if (empty($this->cookies[$set_cookie->getDomain()][$set_cookie->getPath()])) {
            $this->cookies[$set_cookie->getDomain()][$set_cookie->getPath()] = [];
        }
        $this->cookies[$set_cookie->getDomain()][$set_cookie->getPath()][$set_cookie->getName()] = $set_cookie;
        return true;
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

    public static function parseUrl($url): array
    {
        $parse = parse_url($url);
        if (empty($parse['path'])) {
            $parse['path'] = '/';
        }
        $parse['path'] = self::fixPath($parse['path']);
        return $parse;
    }
}