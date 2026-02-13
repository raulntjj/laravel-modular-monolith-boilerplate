<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Application\Queries;

use Illuminate\Foundation\Testing\TestCase;
use Mockery;
use Modules\User\Application\DTOs\UserDTO;
use Modules\User\Application\Queries\GetAuthenticatedUserQuery;
use Modules\Shared\Domain\Contracts\JwtServiceInterface;

final class GetAuthenticatedUserQueryTest extends TestCase
{
    private JwtServiceInterface $jwtService;
    private GetAuthenticatedUserQuery $query;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwtService = Mockery::mock(JwtServiceInterface::class);
        $this->query = new GetAuthenticatedUserQuery($this->jwtService);
    }

    public function test_returns_user_dto_when_authenticated(): void
    {
        $mockUser = Mockery::mock();
        $mockUser->shouldReceive('toArray')
            ->once()
            ->andReturn([
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'John',
                'surname' => 'Doe',
                'email' => 'john@example.com',
                'profile_path' => '/uploads/avatar.jpg',
                'created_at' => '2025-01-15 10:30:00',
                'updated_at' => '2025-01-16 12:00:00',
            ]);

        $this->jwtService
            ->shouldReceive('getAuthenticatedUser')
            ->once()
            ->andReturn($mockUser);

        $result = $this->query->execute();

        $this->assertInstanceOf(UserDTO::class, $result);
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $result->id);
        $this->assertEquals('John', $result->name);
        $this->assertEquals('Doe', $result->surname);
        $this->assertEquals('john@example.com', $result->email);
        $this->assertEquals('/uploads/avatar.jpg', $result->profilePath);
    }

    public function test_returns_null_when_not_authenticated(): void
    {
        $this->jwtService
            ->shouldReceive('getAuthenticatedUser')
            ->once()
            ->andReturn(null);

        $result = $this->query->execute();

        $this->assertNull($result);
    }

    public function test_returns_user_dto_with_required_fields_only(): void
    {
        $mockUser = Mockery::mock();
        $mockUser->shouldReceive('toArray')
            ->once()
            ->andReturn([
                'id' => 'user-id-123',
                'name' => 'Jane',
                'surname' => null,
                'email' => 'jane@test.com',
                'profile_path' => null,
                'created_at' => '2025-02-01 08:00:00',
                'updated_at' => null,
            ]);

        $this->jwtService
            ->shouldReceive('getAuthenticatedUser')
            ->once()
            ->andReturn($mockUser);

        $result = $this->query->execute();

        $this->assertInstanceOf(UserDTO::class, $result);
        $this->assertEquals('user-id-123', $result->id);
        $this->assertEquals('Jane', $result->name);
        $this->assertNull($result->surname);
        $this->assertEquals('jane@test.com', $result->email);
        $this->assertNull($result->profilePath);
    }

    public function test_converts_user_object_to_dto(): void
    {
        $mockUser = Mockery::mock();
        $mockUser->shouldReceive('toArray')
            ->once()
            ->andReturn([
                'id' => 'test-user-id',
                'name' => 'Test User',
                'surname' => 'Surname',
                'email' => 'test@example.com',
                'profile_path' => '/path/to/profile.jpg',
                'created_at' => '2025-01-01 00:00:00',
                'updated_at' => '2025-01-02 00:00:00',
            ]);

        $this->jwtService
            ->shouldReceive('getAuthenticatedUser')
            ->once()
            ->andReturn($mockUser);

        $result = $this->query->execute();

        $array = $result->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('test-user-id', $array['id']);
        $this->assertEquals('Test User', $array['name']);
        $this->assertEquals('Surname', $array['surname']);
        $this->assertEquals('test@example.com', $array['email']);
    }

    public function test_handles_user_with_array_format(): void
    {
        // O método toArray() pode retornar array ou objeto ser array
        $mockUser = Mockery::mock();
        $mockUser->shouldReceive('toArray')
            ->once()
            ->andReturn([
                'id' => 'array-user-id',
                'name' => 'Array User',
                'surname' => null,
                'email' => 'array@test.com',
                'profile_path' => null,
                'created_at' => '2025-02-12 10:00:00',
                'updated_at' => null,
            ]);

        $this->jwtService
            ->shouldReceive('getAuthenticatedUser')
            ->once()
            ->andReturn($mockUser);

        $result = $this->query->execute();

        $this->assertInstanceOf(UserDTO::class, $result);
        $this->assertEquals('array-user-id', $result->id);
    }
}
