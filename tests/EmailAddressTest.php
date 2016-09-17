<?php
/**
 * Unit tests for EmailAddress value object.
 * 
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */
namespace Test\Embeddable;

use DDD\Embeddable\EmailAddress;

class EmailAddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidEmailProvider
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAddressesAreNotAccepted($email)
    {
        $addr = new EmailAddress($email);
    }

    /**
     * @dataProvider validEmailProvider
     */
    public function testValidAddressesAreAccepted($email, $domain, $localPart)
    {
        $obj = new EmailAddress($email);
        $this->assertEquals($email, (string) $obj);
        $this->assertEquals($domain, $obj->getDomain());
        $this->assertEquals($localPart, $obj->getLocalPart());
    }

    public function invalidEmailProvider()
    {
        return [
            ['fake-email'],
            ['fakemail@'],
            ['w@fake mail.com'],
            ['v@for.vendetta/com'],
            ['!?#- 1*@bad.c0m'],
            ['Abc.example.com'],
            ['A@b@c@example.com'],
            ['a"b(c)d,e:f;g<h>i[j\k]l@example.com'],
            ['just"not"right@example.com'],
            ['john..doe@example.com'],
        ];
    }

    public function validEmailProvider()
    {
        return [
            ['devil@hell.travel', 'hell.travel', 'devil'],
            ['jack+london@gmail.com', 'gmail.com', 'jack+london'],
            ['cool@mail.com', 'mail.com', 'cool'],
            ['great@mail.co.uk', 'mail.co.uk', 'great'],
            ['good@email.address.com', 'email.address.com', 'good'],
            ['sogood@sub.domain.com.br', 'sub.domain.com.br', 'sogood'],
            ['a1@b2.eu', 'b2.eu', 'a1'],
        ];
    }
}
