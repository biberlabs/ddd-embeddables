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
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    /**
     * @dataProvider invalidEmailProvider
     */
    public function testInvalidAddressesAreNotAccepted(string $email) : void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmailAddress($email);
    }

    /**
     * @dataProvider validEmailProvider
     */
    public function testValidAddressesAreAccepted(string $email, string $expectedDomain, string $expectedLocalPart) : void
    {
        // Given
        $underTest = new EmailAddress($email);

        // When Then
        $this->assertSame($email, (string) $underTest);
        $this->assertSame($expectedDomain, $underTest->getDomain());
        $this->assertSame($expectedLocalPart, $underTest->getLocalPart());
    }

    public function testEmptyState() : void
    {
        // Given
        $underTest = new EmailAddress();

        // When Then
        $this->assertInstanceOf(EmailAddress::class, $underTest);
        $this->assertNull($underTest->getDomain());
        $this->assertNull($underTest->getLocalPart());
    }

    private function invalidEmailProvider() : array
    {
        return [
            ['fake-email'],
            ['fake-mail@'],
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

    private function validEmailProvider() : array
    {
        return [
            ['devil@hell.travel', 'hell.travel', 'devil'],
            ['jack+london@gmail.com', 'gmail.com', 'jack+london'],
            ['cool@mail.com', 'mail.com', 'cool'],
            ['great@mail.co.uk', 'mail.co.uk', 'great'],
            ['good@email.address.com', 'email.address.com', 'good'],
            ['so-good@sub.domain.com.br', 'sub.domain.com.br', 'so-good'],
            ['a1@b2.eu', 'b2.eu', 'a1'],
        ];
    }
}
