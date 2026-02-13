<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Application\UseCases;

use Illuminate\Foundation\Testing\TestCase;
use Mockery;
use Modules\User\Application\UseCases\RefreshTokenUseCase;
use Modules\Shared\Domain\Contracts\JwtServiceInterface;

final class RefreshTokenUseCaseTest extends TestCase
{
    private JwtServiceInterface $jwtService;
    private RefreshTokenUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jwtService = Mockery::mock(JwtServiceInterface::class);
        $this->useCase = new RefreshTokenUseCase($this->jwtService);
    }

    public function test_handles_refresh_token_exception(): void
    {
        $this->jwtService
            ->shouldReceive('refreshToken')
            ->once()
            ->andThrow(new \RuntimeException('Token refresh failed'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Token refresh failed');

        $this->useCase->execute();
    }
}
