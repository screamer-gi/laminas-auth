<?php

namespace Gi\LaminasAuth;

use Interop\Container\ContainerInterface;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthAdapterFactory implements FactoryInterface
{
    public const CONFIG_KEY = 'users';

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new AuthAdapter(new Bcrypt(), $this->getConfig($container));
    }

    protected function getConfig(ContainerInterface $container): array
    {
        $config = $container->get('config');
        if (!empty($config[self::CONFIG_KEY]) && is_array($config[self::CONFIG_KEY])) {
            return $config[self::CONFIG_KEY];
        }

        return [];
    }
}
