<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Domain\ValueObjects;

use InvalidArgumentException;
use Modules\User\Domain\ValueObjects\Password;
use Illuminate\Foundation\Testing\TestCase;

final class PasswordTest extends TestCase
{
    public function test_creates_password_from_plain_text(): void
    {
        $password = Password::fromPlainText('MySecurePass123');

        $this->assertInstanceOf(Password::class, $password);
        $this->assertNotEmpty($password->value());
    }

    public function test_throws_exception_for_short_password(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 8 characters long');

        Password::fromPlainText('short');
    }

    public function test_verifies_correct_password(): void
    {
        $plainPassword = 'MySecurePass123';
        $password = Password::fromPlainText($plainPassword);

        $this->assertTrue($password->verify($plainPassword));
    }

    public function test_rejects_incorrect_password(): void
    {
        $password = Password::fromPlainText('MySecurePass123');

        $this->assertFalse($password->verify('WrongPassword'));
    }

    public function test_creates_password_from_hash(): void
    {
        $hash = password_hash('test', PASSWORD_ARGON2ID);
        $password = Password::fromHash($hash);

        $this->assertEquals($hash, $password->value());
    }
}
