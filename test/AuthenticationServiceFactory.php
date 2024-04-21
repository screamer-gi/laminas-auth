<?php

declare(strict_types=1);

namespace ApplicationTest;

use Application\Auth\AuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\NonPersistent;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthenticationService
    {
        $authAdapter = $container->get(AuthAdapter::class);
        $authStorage = new NonPersistent();

        return new AuthenticationService($authStorage, $authAdapter);
    }
}