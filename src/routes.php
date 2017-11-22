<?php
// Routes

// $app->get('/[{name}]', function (Request $request, Response $response, array $args) {
//     // Render index view
//     return $this->renderer->render($response, 'template.php', [
//         'name' => $args['name'] ?? '',
//         'viewName' => 'home'
//     ]);
// });

$app->get('/', 'LoginController:index');

$app->map(['GET', 'POST'], '/login', 'LoginController:index');

$app->get('/logout', 'LoginController:logout');

$app->get('/home', 'HomeController:index');

$app->group('/caroneiro', function() use ($app) {
    $app->get('', 'PessoaController:index')->setName('caroneiro');
    $app->get('/pagina/{page}', 'PessoaController:index');
    $app->get('/alterar/{id}', 'PessoaController:alterar');
    $app->get('/excluir/{id}', 'PessoaController:deletar');
    $app->post('/cadastrar', 'PessoaController:cadastrar');
});

$app->group('/carro', function() use ($app) {
    $app->get('', 'CarroController:index');
    $app->get('/pagina/{page}', 'CarroController:index');
    $app->get('/alterar/{id}', 'CarroController:alterar');
    $app->get('/excluir/{id}', 'CarroController:deletar');
    $app->post('/cadastrar', 'CarroController:cadastrar');
});

$app->group('/rota', function() use ($app) {
    $app->get('', 'RotaController:index');
    $app->get('/pagina/{page}', 'RotaController:index');
    $app->get('/alterar/{id}', 'RotaController:alterar');
    $app->get('/excluir/{id}', 'RotaController:deletar');
    $app->post('/cadastrar', 'RotaController:cadastrar');
});

$app->group('/carona', function() use ($app) {
    $app->get('', 'CaronaController:index');
    $app->get('/pagina/{page}', 'CaronaController:index');
    $app->get('/alterar/{id}', 'CaronaController:alterar');
    $app->get('/excluir/{id}', 'CaronaController:deletar');
    $app->post('', 'CaronaController:index');
    $app->post('/cadastrar', 'CaronaController:cadastrar');
});
