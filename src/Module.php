<?php

declare(strict_types=1);

namespace Atto\Framework;

use Atto\Framework\Application\DefaultApplication;
use Atto\Framework\Module\ModuleInterface;
use Atto\Framework\Response\Builder;
use Atto\Framework\Response\Errors\ApiProblemHandler;
use Atto\Framework\Response\Errors\ErrorConverter;
use Atto\Framework\Response\Errors\ErrorHandler;
use Nyholm\Psr7\Factory\Psr17Factory;

final class Module implements ModuleInterface
{
    public function getServices(): array
    {
        return [
            DefaultApplication::class => [
                'args' => [
                    Builder::class
                ]
            ],
            Builder::class => [
                'args' => [
                    ErrorConverter::class,
                    Psr17Factory::class
                ]
            ],
            ErrorConverter::class => [
                'args' => [
                    Psr17Factory::class,
                    ErrorHandler::class,
                    'debug',
                ]
            ],
            Psr17Factory::class => [],
            ApiProblemHandler::class => [
                'args' => [
                    'debug'
                ],
                'tags' => [
                    ErrorHandler::class
                ]
            ]
        ];
    }

    public function getConfig(): array
    {
        return [];
    }
}