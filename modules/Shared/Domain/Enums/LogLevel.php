<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Enums;

enum LogLevel: string
{
    case DEBUG = 'debug';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
    case CRITICAL = 'critical';
    case EVENT = 'event';
    case AUDIT = 'audit';
}
