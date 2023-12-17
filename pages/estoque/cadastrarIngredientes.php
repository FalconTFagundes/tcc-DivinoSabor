<?php

include_once '../config/constantes.php';
include_once '../config/conexao.php';
include_once '../func/func.php';


$nomeIngred = filter_input(INPUT_POST, 'nomeIngred', FILTER_SANITIZE_STRING);
$quantIngred = filter_input(INPUT_POST, 'quantIngred', FILTER_SANITIZE_STRING);
$pesoIngred = filter_input(INPUT_POST, 'pesoIngred', FILTER_SANITIZE_STRING);
$valIngred = filter_input(INPUT_POST, 'valIngred', FILTER_SANITIZE_STRING);
$dataComp = filter_input(INPUT_POST, 'dataCompra', FILTER_SANITIZE_STRING);
$dataValidade = filter_input(INPUT_POST, 'dataValidade', FILTER_SANITIZE_STRING);



$resultado =  insertseis('ingredientes', ' nomeIngred, quantIngred, pesoUnit, precoUnit, dataComp, dataValidad', 
$nomeIngred, $quantIngred, $pesoIngred, $valIngred, $dataComp, $dataValidade);


if ($resultado === "Cadastrado") {
 

}