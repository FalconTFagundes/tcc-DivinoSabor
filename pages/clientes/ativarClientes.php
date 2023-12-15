<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $idCliente = $dados['id'];
    $ativarCliente = $dados['a'];
    
    if($ativarCliente == 'A'){
        $retorno = upUm('clientes', 'ativo', 'idclientes', 'A', "$idCliente");
    } else {
        $retorno = upUm('clientes', 'ativo', 'idclientes', 'D', "$idCliente");
    }



    
} else {
    echo json_encode('Ativar não concluido!! ');
}

echo json_encode($retorno);