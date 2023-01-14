<?php

declare(strict_types=1);

namespace Atto\Framework\Config;

use League\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

final class ConfigContainer implements ContainerInterface
{
    public function __construct(
        private readonly string $alias,
        private readonly array $config
    ) {
    }

    public function get(string $id)
    {
        $keyList = explode('.', $id);
        // Drop the container identifier eg "config"
        $check = array_shift($keyList);
        if ($check !== $this->alias) {
            throw new NotFoundException(
                sprintf('Alias (%s) is not being managed by the container or delegates', $id)
            );
        }

        $config = $this->config;
        foreach ($keyList as $key) {
            if (!isset($config[$key])) {
                throw new NotFoundException(
                    sprintf('Alias (%s) is not being managed by the container or delegates', $id)
                );
            }

            $config = $config[$key];
        }

        return $config;
    }

    public function has(string $id)
    {
        $keyList = explode('.', $id);
        $check = array_shift($keyList);
        if ($check !== $this->alias) {
            return false;
        }

        $config = $this->config;
        foreach ($keyList as $key) {
            if (!isset($config[$key])) {
                return false;
            }

            $config = $config[$key];
        }

        return true;
    }
}