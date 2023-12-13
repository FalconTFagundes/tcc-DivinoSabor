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

// configura uma trava caso haja muitas falhas no login (exemplo)
define('TEMPOFALHA', '15');
define('TENTATIVAFALHA', '3');

// define o horário e data atual
define('DATATIMEATUAL', date("Y-m-d H:i:s"));


//----------------------------------------------------------------


$servidorLocal = true;
if ($servidorLocal) { //se for == true
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', '');
    define('DBNAME', 'db_divinosabor');
} else {
    define('HOST', '15.235.55.95');
    define('USER', 'rafael');
    define('PASS', 'rafaelfagundes762');
    define('DBNAME', 'db_divinosabor');
}


