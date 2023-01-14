<?php

declare(strict_types=1);

namespace Atto\Framework\Response;

interface HasResponseInfo
{
    public function getStatusCode(): int;
}