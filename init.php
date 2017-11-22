<?php
/*
 * Script de inicialização (Bootstrapping)
 *
 * Leia mais sobre Bootstrapping e Arquivos de Inicialização no link abaixo
 * http://rberaldo.com.br/bootstrapping-php-arquivo-inicializacao/
 */

// mantém a sessão sempre ativa
session_start();

// define o diretório base da aplicação
define( 'APP_ROOT_PATH', dirname( __FILE__ ) );

// inclui o autoloader do Composer
require_once 'vendor/autoload.php';
