<?php
/*
 * Created by PhpStorm.
 * User: Luciano
 * Date: 19/10/2019
 * Time: 14:31
 */


//-------------configuração banco de dados

// configura o horário brasileiro pro site/xampp/banco
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR');

// inicia as sessions no projeto
session_start();

// define('URLBASEPATH', __DIR__ . '/../');
// define('BASEPATH', __DIR__ . DIRECTORY_SEPARATOR);
// define('BASEPATHFILE', __FILE__);
// define('BASEPATHVIRTUAL', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
// define('DOMINIO', $_SERVER['SERVER_NAME']);
// define('TITULOSITE', 'NÚCLEO INVESTIGACION INTERNACIONAL EN CIENCIAS MEDICAS');

// configura uma trava caso haja muitas falhas no login (exemplo)
define('TEMPOFALHA', '15');
define('TENTATIVAFALHA', '3');

// define o horário e data atual
define('DATATIMEATUAL', date("Y-m-d H:i:s"));


//----------------------------------------------------------------


$servidorLocal = true;
if ($servidorLocal) {
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', '');
    define('DBNAME', 'dbcadastro');
} else {
    define('HOST', '0');
    define('USER', '0');
    define('PASS', '0');
    define('DBNAME', '0');
}


