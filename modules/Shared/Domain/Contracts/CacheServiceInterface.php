<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

interface CacheServiceInterface
{
    /**
     * Obtém um valor do cache
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Armazena um valor no cache
     */
    public function put(string $key, mixed $value, ?int $ttl = null): bool;

    /**
     * Armazena um valor no cache permanentemente
     */
    public function forever(string $key, mixed $value): bool;

    /**
     * Obtém ou armazena um valor usando callback
     */
    public function remember(string $key, int $ttl, callable $callback): mixed;

    /**
     * Remove um item do cache
     */
    public function forget(string $key): bool;

    /**
     * Remove múltiplos itens do cache
     */
    public function forgetMany(array $keys): bool;

    /**
     * Invalida cache por tags
     */
    public function invalidateTags(array $tags): bool;

    /**
     * Verifica se uma chave existe no cache
     */
    public function has(string $key): bool;

    /**
     * Limpa todo o cache
     */
    public function flush(): bool;

    /**
     * Incrementa um valor numérico no cache
     */
    public function increment(string $key, int $value = 1): int|false;

    /**
     * Decrementa um valor numérico no cache
     */
    public function decrement(string $key, int $value = 1): int|false;

    /**
     * Obtém múltiplos valores do cache
     */
    public function many(array $keys): array;

    /**
     * Define múltiplos valores no cache
     */
    public function putMany(array $values, ?int $ttl = null): bool;
}
