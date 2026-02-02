<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Concerns;

trait ModuleAware
{
    private function extractModuleName(string $className): string
    {
        // Extrai o nome do módulo do namespace
        // Ex: Modules\User\Application\UseCases\CreateUser -> User
        if (preg_match('/^Modules\\\\([^\\\\]+)\\\\/', $className, $matches)) {
            return $matches[1];
        }

        return 'Unknown';
    }
}
