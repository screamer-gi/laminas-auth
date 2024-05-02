<?php

declare(strict_types=1);

use Gi\LaminasAuth\AuthAdapter;
use Gi\LaminasAuth\AuthAdapterFactory;
use Gi\LaminasAuth\AuthController;
use Gi\LaminasAuth\AuthenticationServiceFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\Di\Container\AutowireFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;

return [
    'router' => [
        'routes' => [
            'auth-login' => [
                'type'    => 'literal',
                'options' => [
                    'route'       => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'auth-logout' => [
                'type'    => 'literal',
                'options' => [
                    'route'       => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'controllers'  => [
        'factories' => [
            AuthController::class => AutowireFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthAdapter::class => AuthAdapterFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'gi/laminas-auth/auth/login' => __DIR__ . '/../view/auth/login.phtml',
        ],
    ],
    'session_config' => [
        'cookie_lifetime' => 3600,
        'gc_maxlifetime' => 86400 * 30,
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ],
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class,
    ],
];
