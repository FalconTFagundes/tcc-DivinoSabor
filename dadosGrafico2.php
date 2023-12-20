<?php

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$retornoDadosGrafico2 = capturarPacotesBest();

$dados = [
    'labels' => [],
    'data' => [],
];

foreach ($retornoDadosGrafico2 as $row) {
    $dados['labels'][] = $row['nome_pacote'];
    $dados['data'][] = $row['total_vendas'];
}

echo json_encode($dados);