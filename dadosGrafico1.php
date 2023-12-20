<?php

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$retornoDadosGrafico2 = capturarVendasMes();

$dados = [
    'labels' => [],
    'data' => [],
];

foreach ($retornoDadosGrafico2 as $row) {
    $dados['labels'][] = $row['mes'];
    $dados['data'][] = $row['total_registros'];
}

echo json_encode($dados);
