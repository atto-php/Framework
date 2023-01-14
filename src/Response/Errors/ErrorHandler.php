<?php

declare(strict_types=1);

namespace Atto\Framework\Response\Errors;

use Crell\ApiProblem\ApiProblem;

interface ErrorHandler
{
    public function supports(\Throwable $throwable): bool;

    public function handle(\Throwable $throwable): ApiProblem;
}