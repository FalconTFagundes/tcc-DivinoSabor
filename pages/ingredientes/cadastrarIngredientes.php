<?php

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if (!empty($dados) && isset($dados)) {

    $nomeIngrediente = $dados['nomeIngred'];
    $qtdIngrediente = $dados['quantIngred'];
    $pesoIngrediente = $dados['pesoIngred'];
    $valorIngred = $dados['valorIngred'];
    $dataCompraIngrediente = $dados['dataCompra'];
    $codigoIngrediente = $dados['codigoIngrediente'];
    $dataValidade = $dados['dataValidade'];

    $dataeHoraAtual = date('Y-m-d H:i:s');

    $resultado =  insertOito(
        'ingredientes',
        'nomeIngred, quantIngred, pesoUnit, precoUnit, dataComp, dataValidad, codigo, cadastro',
        "$nomeIngrediente",
        "$qtdIngrediente",
        "$pesoIngrediente",
        "$valorIngred",
        "$dataCompraIngrediente",
        "$dataValidade",
        "$codigoIngrediente",
        "$dataeHoraAtual"
    );
} else {
    echo json_encode('Erro no Insert');
}
