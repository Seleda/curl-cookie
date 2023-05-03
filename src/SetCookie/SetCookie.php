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
                $this->expires = $matches[1];
            } elseif (preg_match('/^Max-Age=(.*)/i', $val, $matches)) {
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

    public function getMaxAge()
    {
        return $this->maxAge;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getSecure()
    {
        return $this->secure;
    }

    public function getHttpOnly()
    {
        return $this->httpOnly;
    }

    public function getSameSite()
    {
        return $this->sameSite;
    }
}