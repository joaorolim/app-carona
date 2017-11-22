<?php

namespace Fatec\Controllers;

use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if ($request->getMethod() == "POST") {
            $this->login($request, $response, $args);
        } elseif ($request->getMethod() == "GET") {
            $this->formLogin($request, $response, $args);
        }
    }

    private function formLogin(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->c->renderer->render($response, 'template_login.php', [
            'name' => $args['name'] ?? '',
            'viewName' => 'login'
        ]);
    }

    private function login(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $allPostVars = $request->getParsedBody();
        $email = $allPostVars['email'];
        $pass = $allPostVars['pass'];

        if ($email == 'jaime' && $pass == '12345') {
            redirect(getBaseURL().'home');
            exit();
        }
        $this->formLogin($request, $response, $args);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $response->withRedirect('/login');
        exit();
    }
}
