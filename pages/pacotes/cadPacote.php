<?php


include_once '../../config/constantes.php';
include_once '../../config/conexao.php';
include_once '../../func/dashboard.php';



$nomepacote = filter_input(INPUT_POST, 'nomePacote', FILTER_SANITIZE_STRING);
$imgPacote = filter_input(INPUT_POST, 'imgPacote', FILTER_SANITIZE_STRING);
$quantPessoas = filter_input(INPUT_POST, 'quantPessoas', FILTER_SANITIZE_STRING);


$resultado =  insertTres('pacote', 'pacote, img, qtd ', 
$nomepacote, $imgPacote, $quantPessoas);


if ($resultado === "Cadastrado") {
 

}