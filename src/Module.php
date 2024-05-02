<?php

declare(strict_types=1);

namespace Gi\LaminasAuth;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent|EventInterface $e): void
    {
        $eventManager = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(
            AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, $this->onDispatch(...),
            100,
        );
    }

    public function onDispatch(MvcEvent $event) // todo return type
    {
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller');
        /** @var AuthManager $authManager */
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        if (!$authManager->filterAccess($controllerName)) {
            $uri = $event->getApplication()->getRequest()->getUri();
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            return $controller->redirect()->toRoute('auth-login', [], $redirectUrl == '/' ? [] : [
                'query' => [
                    'r' => $redirectUrl,
                ],
            ]);
        }
        return null;
    }
}
