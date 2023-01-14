<?php

declare(strict_types=1);

namespace Atto\Framework\Response\Errors;

interface HasApiProblem
{
    public function getStatusCode(): int;
    public function getType(): ?string;
    public function getTitle(): ?string;
    public function getDetail(): ?string;

    /** @return array<string, mixed> */
    public function getAdditionalInformation(): array;
    /** @return array<string, mixed> */
    public function getDebugInformation(): array;
}