<?php


namespace Seleda\CurlCookie\SetCookie;


abstract class SetCookie
{
    protected string $name = '';
    protected string $value = '';
    protected string $expires = '';
    protected int $maxAge = 0;
    protected string $domain = '';
    protected string $path = '/';
    protected bool $secure = false;
    protected bool $httpOnly = false;
    protected string $sameSite = '';

    public function __construct(string $set_cookie) {
        $line = $this->getline($set_cookie);
        $pieces = array_filter(array_map('trim', explode(';', $line)));
        $required = array_shift($pieces);
        $exp_required = explode('=', $required);
        $this->name = $exp_required[0];
        $this->value = implode('=', array_slice($exp_required, 1));
        foreach ($pieces as $val) {
            if (preg_match('/^expires=(.*)/i', $val, $matches)) {
                // Example: Fri, 02-Jun-2023 01:38:25 GMT
                $this->expires = $matches[1];
                // Устанавливаем на Max-Age для облегчения сравнения
                $this->maxAge = strtotime($this->expires);
            } elseif (preg_match('/^Max-Age=(.*)/i', $val, $matches)) {
                // Переводим на Expires
                // Имеет приоритет перед Expires
                $this->expires = date('D, d-M-Y H:i:s e', time() + $matches[1]);
                $this->maxAge = $matches[1];
            } elseif (preg_match('/^domain=(.*)/i', $val, $matches)) {
                $this->domain = $matches[1];
            } elseif (preg_match('/^path=(.*)/i', $val, $matches)) {
                $this->path = $matches[1];
            } elseif ($val == 'Secure') {
                $this->secure = true;
            } elseif ($val == 'HttpOnly') {
                $this->httpOnly = true;
            } elseif (preg_match('/^SameSite=(.*)/i', $val, $matches)) {
                $this->sameSite = $matches[1];
            } else {
                throw new \Exception('Unknown key');
            }
        }
    }

    abstract protected function getLine(string $set_cookie):string;

    public function getName():string
    {
        return $this->name;
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function getMaxAge():int
    {
        return $this->maxAge;
    }

    public function getDomain():string
    {
        return $this->domain;
    }

    public function getPath():string
    {
        return $this->path;
    }

    public function getSecure():bool
    {
        return $this->secure;
    }

    public function getHttpOnly():bool
    {
        return $this->httpOnly;
    }

    public function getSameSite():string
    {
        return $this->sameSite;
    }
}