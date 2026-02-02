<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Logging\Concerns;

use Modules\Shared\Domain\Contracts\LoggerInterface;
use Modules\Shared\Infrastructure\Concerns\ModuleAware;
use Modules\Shared\Infrastructure\Logging\LoggerFactory;

trait Loggable
{
    use ModuleAware;

    protected function logger(): LoggerInterface
    {
        $className = static::class;
        $moduleName = $this->extractModuleName($className);

        return LoggerFactory::forModule($moduleName);
    }
}
