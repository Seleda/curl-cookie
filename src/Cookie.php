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

        // Проверка правил установки  с префиксом
        if ($scheme == 'http' || !$set_cookie->getSecure()) {
            if (substr($set_cookie->getName(), 0, 8) == '__Secure' ||
                substr($set_cookie->getName(), 0, 6) == '__Host') {
                return false;
            }
        }
        if (substr($set_cookie->getName(), 0, 6) == '__Host') {
            if ($set_cookie->getPath() != '/' || $set_cookie->getDomain()) {
                return false;
            }
        }
        //

        if (!$set_cookie->getDomain()) {
            $set_cookie->setDomain($domain);
        }

        $set_cookie_path = $set_cookie->getPath() ? $set_cookie->getPath() : '/';

        if (empty($this->cookies[$set_cookie->getDomain()])) {
            $this->cookies[$set_cookie->getDomain()] = [];
        }
        if (empty($this->cookies[$set_cookie->getDomain()][$set_cookie_path])) {
            $this->cookies[$set_cookie->getDomain()][$set_cookie_path] = [];
        }
        $this->cookies[$set_cookie->getDomain()][$set_cookie_path][$set_cookie->getName()] = $set_cookie;
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
            //TODO удалить сессионные cookies при сохранении
            if ($set_cookie->getExpires() && strtotime($set_cookie->getExpires()) < time()) {
                continue;
            }
            if ($parse['scheme'] == 'http' && $set_cookie->getSecure()) {
                 continue;
            }
            $cookies .= (strlen($cookies) > 0 ? ' ' : '') . $set_cookie->getName().'='.$set_cookie->getValue().';';
        }

        return $cookies;
    }

    public static function reset()
    {
        if (self::$instance) {
            self::$instance->cookies = [];
        }
    }

    public static function fixPath(string $path = null): string
    {
        if (empty($path) || $path == '/') {
            return '/';
        }
        return rtrim($path, '/');;
    }

    public static function parseUrl($url): array
    {
        $parse = parse_url($url);
        $parse['path'] = self::fixPath($parse['path']);
        return $parse;
    }
}