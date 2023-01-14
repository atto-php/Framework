<?php

declare(strict_types=1);

namespace Atto\Framework\Container;

use Laminas\Stdlib\ArrayUtils;
use League\Container\Container;

final class Compiler
{
    public function __construct(private array $config)
    {

    }

    public function compileConfig(): array
    {
        $config = [];
        foreach ($this->config as $moduleConfig) {
            $config = ArrayUtils::merge($config, $moduleConfig);
        }

        $container = new Container();
        $container->delegate(new ConfigContainer('env', getenv()));
        $container->delegate(new ConfigContainer('config', $config));


        array_walk_recursive($config, function (&$value, $key) use ($container) {
            while (($value instanceof Reference)) {
                if (!$container->has($value->to)) {
                    throw new \Exception('Unable to resolve reference');
                }

                $value = $container->get($value->to);
            }
        });

        return $config;
    }
}