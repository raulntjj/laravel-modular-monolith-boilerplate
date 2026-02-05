<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Domain\ValueObjects;

use InvalidArgumentException;
use Modules\User\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_creates_valid_email(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', $email->value());
    }

    public function test_converts_email_to_lowercase(): void
    {
        $email = new Email('TEST@EXAMPLE.COM');

        $this->assertEquals('test@example.com', $email->value());
    }

    public function test_throws_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        new Email('invalid-email');
    }

    public function test_equals_method_works_correctly(): void
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $email3 = new Email('other@example.com');

        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }

    public function test_converts_to_string(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', (string) $email);
    }
}
