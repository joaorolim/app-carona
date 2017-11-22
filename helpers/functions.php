<?php
/**
 * Ultimate PHPerguntas
 *
 * Este script faz parte do Projeto Prático do curso Ultimate PHP.
 * O Ultimate PHP é um curso voltado para iniciantes e intermediários em PHP.
 * Conheça o curso Ultimate PHP acessando http://www.ultimatephp.com.br
 *
 * O projeto completo está disponível no Github: https://github.com/beraldo/UltimatePHPerguntas
 *
 * @author: Roberto Beraldo Chaiben
 * @package Ultimate PHPerguntas
 * @link http://www.ultimatephp.com.br
 */

/**
 * Arquivo de funções para uso geral
 */

/**
 * Verifica se o ambiente atual é de desenvolvimento
 * @return boolean Retorna TRUE se for ambiente de desenvolvimento, FALSE caso contrário
 */
function isDevEnv()
{
    return ENV == 'dev';
}

/**
 * Retorna o caminho para o diretório com as views
 * @return string caminho para o diretório com as views
 */
function viewsPath()
{
    return APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
}

/**
 * Retorna o caminho para o diretório de logs
 * @return string caminho para o diretório de logs
 */
function logsPath()
{
    return APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
}

/**
 * Retorna a URL base da aplicação
 * @return string URL base da aplicação
 */
function getBaseURL()
{
    return sprintf(
        "%s://%s%s/",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT']
    );
}

/**
 * Retorna a URL atual
 * @return string URL atual
 */
function getCurrentURL()
{
    return getBaseURL() . $_SERVER['REQUEST_URI'];
}


/**
 * Função de redirecionamento HTTP
 * @param  string $url URL de destino
 */
function redirect( $url )
{
    header( 'Location: ' . $url );
    exit;
}

function reais($decimal) {
    return "R$" . number_format($decimal,2,",",".");
}


function dataBr_to_dataMySQL($data) {
    $campos = explode("/", $data);
    return date("Y-m-d", strtotime($campos[2]."/".$campos[1]."/".$campos[0]));
}


function dataMySQL_to_dataBr($data) {
    return date("d/m/Y", strtotime($data));
}

function setMessage( $message, $class, $col=12 )
{
    $msg = '<div class="row">
                <div class="col-md-'.$col.'">
                    <div class="alert-'.$class.'">
                        <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                        <h3>'.$message.'</h3>
                    </div>
                </div>
            </div>';

    $_SESSION['message'] = $msg;

    return true;
}

function getMessage()
{
    $msg = $_SESSION['message'] ?? null;

    $_SESSION['message'] = null;
    unset($_SESSION['message']);

    return $msg;
}

function hasMessage()
{
    return ( ( isset($_SESSION['message']) ) ? true : false);
}

/**
 * Verifica se dois arrays unidimensionais são iguais em tamanho e conteúdo
 * @param  array $array_1 primeiro argumento da função array_diff do PHP
 * @param  array $array_2 segundo argumento da função array_diff do PHP
 * @return boolean true se os arrays são iguais e false caso contrário
 */
function comparaArrays( $array_1, $array_2)
{
    $n1 = count($array_1);
    $n2 = count($array_2);

    if ( ! ($n1 == $n2) ) {
        return false;
    }

    $diff = array_diff( $array_1, $array_2 );

    if ( $diff ) {
        return false;
    }

    return true;
}
