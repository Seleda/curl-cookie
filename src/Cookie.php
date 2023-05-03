<?php


namespace Seleda\CurlCookie;

use Seleda\CurlCookie\CookieSet;

class Cookie
{
    private static array $cookies = [];

    public static function set(array $cookies): void
    {
        foreach ($cookies as $val) {
            $cookie = new CookieSet($val);
            self::$cookies[$cookie->getShema()][$cookie->getDomain()][$cookie->getPath()] = $cookie;
        }
    }

    public static function get(string $url): string
    {
        $parse = parse_url($url);
        $path = self::fixPath($parse['path']);
        $root = isset(self::$cookies[$parse['scheme']][$parse['host']]['/']) ? self::$cookies[$parse['scheme']][$parse['host']]['/'] : [];
        $path = isset(self::$cookies[$parse['scheme']][$parse['host']][$path]) ? self::$cookies[$parse['scheme']][$parse['host']][$path] : [];
        if (count($root) == 0 && count($path) == 0) {
            return '';
        }

        $cookies = '';
        foreach ($root as $cookie_set) {
            $cookies .= (strlen($cookies) > 0 ? '' : '; ') . $cookie_set->get();
        }
        foreach ($path as $cookie_set) {
            $cookies .= (strlen($cookies) > 0 ? '; ' : '') . $cookie_set->get();
        }
        return $cookies;
    }

    private static function fixPath(string $path = null): string
    {
        if (is_null($path)) {
            return '/';
        }
        return $path;
    }

    protected function parseUrl($url)
    {
        $parse = parse_url($url);
        if (is_null($parse['path'])) {
            $parse['path'] = '/';
        }
        return $parse;
    }
}