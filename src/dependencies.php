<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];

    if ($settings['driver'] == 'mysql') {
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );
        $dbName = $settings['database'];
        $host = $settings['host'];
        $username = $settings['username'];
        $password = $settings['password'];

        $db = new \PDO("mysql:dbname={$dbName};host={$host}", $username, $password, $options);
    }

    return $db;
};

$container['Pessoa'] = function ($c) {
    return new \Fatec\Models\Pessoa($c);
};

$container['Carro'] = function ($c) {
    return new \Fatec\Models\Carro($c);
};

$container['Rota'] = function ($c) {
    return new \Fatec\Models\Rota($c);
};

$container['Carona'] = function ($c) {
    return new \Fatec\Models\Carona($c);
};

$container['Fechamento'] = function ($c) {
    return new \Fatec\Models\Fechamento($c);
};

$container['LoginController'] = function ($c) {
    return new \Fatec\Controllers\LoginController($c);
};

$container['HomeController'] = function ($c) {
    return new \Fatec\Controllers\HomeController($c);
};

$container['PessoaController'] = function ($c) {
    return new \Fatec\Controllers\PessoaController($c);
};

$container['CarroController'] = function ($c) {
    return new \Fatec\Controllers\CarroController($c);
};

$container['RotaController'] = function ($c) {
    return new \Fatec\Controllers\RotaController($c);
};

$container['CaronaController'] = function ($c) {
    return new \Fatec\Controllers\CaronaController($c);
};

$container['FechamentoController'] = function ($c) {
    return new \Fatec\Controllers\FechamentoController($c);
};
