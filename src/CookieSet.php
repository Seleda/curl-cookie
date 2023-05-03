<?php


namespace Seleda\CurlCookie;


class CookieSet
{
    private string $name;
    private string $value;
    private string $domain;
    private string $path;
    private string $max_age;
    private string $expires;
    private string $secure;
    private string $discard;
    private string $HttpOnly;

    public function __construct(string $string)
    {
        $pieces = \array_filter(\array_map('trim', \explode(';', $string)));
        $required = array_shift($pieces);
        $exp_required = explode('=', $required);
        $this->name = $exp_required[0];
        foreach ($pieces as $val) {

        }
    }

    public function getString()
    {
        return $this->name.'='.$this->value;
    }
}