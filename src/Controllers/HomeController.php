<?php

namespace Fatec\Controllers;

use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->c->renderer->render($response, 'template.php', [
            'name' => $args['name'] ?? '',
            'viewName' => 'home'
        ]);
    }
}
