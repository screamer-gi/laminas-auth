<?php

declare(strict_types=1);

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use ApplicationTest\AuthTrait;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AuthTest extends AbstractHttpControllerTestCase
{
    use AuthTrait;

    public function setUp(): void
    {
        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            include __DIR__ . '/../../../../config/test.config.php'
        ));
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
        $this->assertRedirectTo('/login');
    }

    public function testLoginPage()
    {
        $this->markTestIncomplete();
    }

    public function testLoginPassed()
    {
        $this->markTestIncomplete();
    }

    public function testLoginFailed()
    {
        $this->markTestIncomplete();
    }

    public function testLoginWhenAuthenticated()
    {
        $this->markTestIncomplete();
    }

    public function testLogout()
    {
        $this->markTestIncomplete();
    }

    public function testLogoutWhenAlreadyLoggedOut()
    {
        $this->markTestIncomplete();
    }
}
