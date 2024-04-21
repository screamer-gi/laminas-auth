<?php

namespace Gi\LaminasAuth;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new Session('auth', 'session', $sessionManager);
        $authAdapter = $container->get(AuthAdapter::class);

        return new AuthenticationService($authStorage, $authAdapter);
    }
}
