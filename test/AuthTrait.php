<?php

declare(strict_types=1);

namespace ApplicationTest;

use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\ServiceManager;

/**
 * @method ServiceManager getApplicationServiceLocator()
 */
trait AuthTrait
{
    public function loggedIn()
    {
        $services = $this->getApplicationServiceLocator();
        $authStorage = $services->get(AuthenticationService::class)->getStorage();
        $authStorage->write('test');
    }
}