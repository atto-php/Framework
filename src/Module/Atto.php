<?php

declare(strict_types=1);

namespace Atto\Framework\Module;

use Atto\Framework\Application\ApplicationInterface;
use Atto\Framework\Application\DefaultApplication;
use Atto\Framework\Config\Compiler;
use Atto\Framework\Config\ConfigContainer;
use League\Container\Container;
use Psr\Container\ContainerInterface;

final class Atto
{
    public const ENV_PROD = 'prod';
    public const ENV_STAGING = 'staging';
    public const ENV_BUILD = 'build';
    public const ENV_TEST = 'test';
    public const ENV_INTEGRATION = 'integration';
    public const ENV_DEV = 'dev';

    private static function loadServices(ContainerInterface $container, array $serviceConfig)
    {
        foreach ($serviceConfig as $service => $config) {
            if (isset($config['class'])) {
                $definition = $container->add($service, $config['class']);
            } elseif (isset($config['factory'])) {
                $definition = $container->add($service, $config['factory']);
            } else {
                $definition = $container->add($service);
            }

            if (isset($config['args'])) {
                $definition->addArguments($config['args']);
            }

            foreach ($config['tags'] ?? [] as $tag) {
                $definition->addTag($tag);
            }

            $definition->setShared($config['shared'] ?? true);
        }
    }

    public static function buildContainer(array $applicationConfig): ContainerInterface
    {
        $config = [];
        if (isset($applicationConfig['globalConfig'])) {
            $config[] = $applicationConfig['globalConfig'];
        }

        $container = new Container();
        $container->add(ContainerInterface::class, $container);

        foreach ($applicationConfig['modules'] as $moduleClass) {
            $module = new $moduleClass($applicationConfig['env']);
            $module instanceof ModuleInterface || throw new \Exception('Module is not a module');

            self::loadServices($container, $module->getServices());
            $config[] = $module->getConfig();
        }

        $configCompiler = new Compiler($config);

        $container->delegate(new ConfigContainer('env', getenv()));
        $container->delegate(new ConfigContainer('config', $configCompiler->compileConfig()));

        foreach ($applicationConfig as $key => $value) {
            $container->add($key, $value);
        }

        return $container;
    }

    public static function init(array $applicationConfig): ApplicationInterface
    {
        $applicationConfig = array_merge([
            'debug' => false,
            'env' => self::ENV_PROD,
            'application' => DefaultApplication::class,
        ], $applicationConfig);

        $container = self::buildContainer($applicationConfig);

        return $container->get($applicationConfig['application']);
    }
}