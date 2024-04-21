<?php

namespace Gi\LaminasAuth;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Session\SessionManager;

class AuthManager
{
    private AuthenticationService $authService;
    private SessionManager $sessionManager;

    public function __construct(AuthenticationService $authService, SessionManager $sessionManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }

    public function login(string $login, string $password, bool $rememberMe): Result
    {
        if ($this->authService->getIdentity() !== null) {
            return new Result(
                Result::SUCCESS,
                'login',
                ['Authenticated successfully.']);
        }

        /** @var AuthAdapter $adapter */
        $adapter = $this->authService->getAdapter();
        $adapter->setLogin($login);
        $adapter->setPassword($password);
        $result = $this->authService->authenticate();

        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
        }

        return $result;
    }

    public function logout(): void
    {
        if ($this->authService->getIdentity()) {
            $this->authService->clearIdentity();
        }
    }

    public function filterAccess($controllerName): bool
    {
        if ($controllerName == AuthController::class) {
            return true;
        }

        return $this->authService->hasIdentity();
    }
}
