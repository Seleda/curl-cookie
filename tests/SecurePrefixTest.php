<?php


use PHPUnit\Framework\TestCase;

class SecurePrefixTest extends TestCase
{
    public function test_first()
    {
        //Оба принимаются, когда исходят из защищенного источника (HTTPS)
        $input1 = 'Set-Cookie: __Secure-ID=123; Secure; Domain=example.com';
        $input2 = 'Set-Cookie: __Host-ID=123; Secure; Path=/';

        // Отклонено из-за отсутствия директивы Secure
        $input1 = 'Set-Cookie: __Secure-id=1';

        // Отклонено из-за отсутствия директивы Path=/
        $input1 = 'Set-Cookie: __Host-id=1; Secure';

        // Отклонено из-за установки домена
        $input1 = 'Set-Cookie: __Host-id=1; Secure; Path=/; domain=example.com';
    }
}
