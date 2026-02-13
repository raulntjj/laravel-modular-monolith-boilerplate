<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Application\DTOs;

use PHPUnit\Framework\TestCase;
use Modules\User\Application\DTOs\LoginDTO;

final class LoginDTOTest extends TestCase
{
    public function test_creates_from_array(): void
    {
        $dto = LoginDTO::fromArray([
            'email' => 'john@example.com',
            'password' => 'SecurePass123',
        ]);

        $this->assertInstanceOf(LoginDTO::class, $dto);
        $this->assertEquals('john@example.com', $dto->email);
        $this->assertEquals('SecurePass123', $dto->password);
    }

    public function test_to_credentials_returns_correct_array(): void
    {
        $dto = new LoginDTO(
            email: 'jane@example.com',
            password: 'AnotherPass456'
        );

        $credentials = $dto->toCredentials();

        $this->assertIsArray($credentials);
        $this->assertArrayHasKey('email', $credentials);
        $this->assertArrayHasKey('password', $credentials);
        $this->assertEquals('jane@example.com', $credentials['email']);
        $this->assertEquals('AnotherPass456', $credentials['password']);
    }

    public function test_credentials_preserves_email_case(): void
    {
        $dto = new LoginDTO(
            email: 'John.Doe@Example.COM',
            password: 'test123'
        );

        $credentials = $dto->toCredentials();

        $this->assertEquals('John.Doe@Example.COM', $credentials['email']);
    }

    public function test_direct_instantiation(): void
    {
        $dto = new LoginDTO(
            email: 'test@test.com',
            password: 'password123'
        );

        $this->assertEquals('test@test.com', $dto->email);
        $this->assertEquals('password123', $dto->password);
    }
}
