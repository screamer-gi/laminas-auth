<?php

namespace Gi\LaminasAuth;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\PasswordInterface;

class AuthAdapter implements AdapterInterface
{
    private string $login;
    private string $password;
    private array $users;
    private PasswordInterface $cryptPassword;

    public function __construct(PasswordInterface $cryptPassword, array $users)
    {
        $this->users = $users;
        $this->cryptPassword = $cryptPassword;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function authenticate(): Result
    {
        if (isset($this->users[$this->login]) && $this->cryptPassword->verify($this->password, $this->users[$this->login])) {
            return new Result(
                Result::SUCCESS,
                $this->login,
                ['Authenticated successfully.']);
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}
