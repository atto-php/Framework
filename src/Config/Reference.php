<?php

declare(strict_types=1);

namespace Atto\Framework\Config;

final class Reference
{
    public function __construct(public readonly string $to)
    {

    }
}