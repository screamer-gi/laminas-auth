<?php

namespace Gi\LaminasAuth;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Uri\Uri;
use Laminas\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    private AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function loginAction()
    {
        $redirectUrl = (string)$this->params()->fromQuery('r', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }
        // todo redirect if logged in

        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = false;
        $error = 'get';

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();

                $result = $this->authManager->login($data['login'], $data['password'], $data['remember_me']);
                if ($result->getCode() == Result::SUCCESS) {
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');
                    if (!empty($redirectUrl)) {
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() !== null) {
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }
                    }

                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                    $error = 'login failed';
                }
            } else {
                $isLoginError = true;
                $error = $form->getMessages();
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl,
            'error' => $error,
        ]);
    }

    public function logoutAction()
    {
        $this->authManager->logout();

        return $this->redirect()->toRoute('auth-login');
    }
}
