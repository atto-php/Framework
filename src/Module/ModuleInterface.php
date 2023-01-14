<?php

declare(strict_types=1);

namespace Atto\Framework\Module;

interface ModuleInterface
{
    public function getServices(): array;

    public function getConfig(): array;
}