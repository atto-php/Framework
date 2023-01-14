<?php

declare(strict_types=1);

namespace Atto\Framework\Application;

use Atto\Framework\Response\Builder;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

final class DefaultApplication implements ApplicationInterface
{
    public function __construct(
        private Builder $responseBuilder,
    ) {
    }

    public function run()
    {
        $result =
            'This is Atto\'s default application class, you should replace it with one which actually does something';

        (new SapiEmitter())->emit($this->responseBuilder->buildResponse($result));
    }
}