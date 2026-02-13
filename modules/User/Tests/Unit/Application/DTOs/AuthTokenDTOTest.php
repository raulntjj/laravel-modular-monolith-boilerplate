<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Application\DTOs;

use PHPUnit\Framework\TestCase;
use Modules\User\Application\DTOs\AuthTokenDTO;

final class AuthTokenDTOTest extends TestCase
{
    public function test_creates_from_token(): void
    {
        $dto = AuthTokenDTO::fromToken('fake.jwt.token', 60);

        $this->assertInstanceOf(AuthTokenDTO::class, $dto);
        $this->assertEquals('fake.jwt.token', $dto->accessToken);
        $this->assertEquals('bearer', $dto->tokenType);
        $this->assertEquals(3600, $dto->expiresIn); // 60 * 60
    }

    public function test_from_token_converts_minutes_to_seconds(): void
    {
        $dto = AuthTokenDTO::fromToken('token123', 120);

        $this->assertEquals(7200, $dto->expiresIn); // 120 * 60
    }

    public function test_from_token_with_one_minute(): void
    {
        $dto = AuthTokenDTO::fromToken('short.token', 1);

        $this->assertEquals(60, $dto->expiresIn);
    }

    public function test_to_array_returns_correct_structure(): void
    {
        $dto = new AuthTokenDTO(
            accessToken: 'my.access.token',
            tokenType: 'bearer',
            expiresIn: 3600
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('access_token', $array);
        $this->assertArrayHasKey('token_type', $array);
        $this->assertArrayHasKey('expires_in', $array);
        $this->assertEquals('my.access.token', $array['access_token']);
        $this->assertEquals('bearer', $array['token_type']);
        $this->assertEquals(3600, $array['expires_in']);
    }

    public function test_to_array_uses_snake_case_keys(): void
    {
        $dto = AuthTokenDTO::fromToken('test.token', 30);

        $array = $dto->toArray();

        // Verifica que as chaves estão em snake_case
        $this->assertArrayNotHasKey('accessToken', $array);
        $this->assertArrayNotHasKey('tokenType', $array);
        $this->assertArrayNotHasKey('expiresIn', $array);
    }

    public function test_direct_instantiation(): void
    {
        $dto = new AuthTokenDTO(
            accessToken: 'direct.token',
            tokenType: 'bearer',
            expiresIn: 7200
        );

        $this->assertEquals('direct.token', $dto->accessToken);
        $this->assertEquals('bearer', $dto->tokenType);
        $this->assertEquals(7200, $dto->expiresIn);
    }

    public function test_readonly_properties(): void
    {
        $dto = new AuthTokenDTO(
            accessToken: 'test.token',
            tokenType: 'bearer',
            expiresIn: 3600
        );

        // Testa que as propriedades são readonly
        $reflection = new \ReflectionClass($dto);
        $this->assertTrue($reflection->isReadOnly());
    }
}
