<?php

declare(strict_types=1);

namespace Atto\Framework\Container;

final class Reference
{
    public function __construct(public readonly string $to)
    {

    }
}