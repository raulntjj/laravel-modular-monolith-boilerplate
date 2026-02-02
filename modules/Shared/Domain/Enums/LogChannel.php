<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Enums;

enum LogChannel: string
{
    case APPLICATION = 'application';
    case DOMAIN = 'domain';
    case INFRASTRUCTURE = 'infrastructure';
    case SECURITY = 'security';
    case PERFORMANCE = 'performance';
    case AUDIT = 'audit';
}
