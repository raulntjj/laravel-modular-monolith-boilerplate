<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

interface JwtServiceInterface
{
    /**
     * Gera um token JWT a partir de credenciais (email + password).
     *
     * @param array{email: string, password: string} $credentials
     * @return string|null Token JWT ou null se credenciais inválidas
     */
    public function attemptLogin(array $credentials): ?string;

    /**
     * Renova o token JWT atual (rotação de token).
     */
    public function refreshToken(): string;

    /**
     * Invalida o token JWT atual (logout).
     */
    public function invalidateToken(): void;

    /**
     * Retorna o usuário autenticado a partir do token.
     *
     * @return mixed Instância do usuário autenticado ou null
     */
    public function getAuthenticatedUser(): mixed;

    /**
     * Retorna o TTL do token em minutos.
     */
    public function getTokenTtl(): int;
}
