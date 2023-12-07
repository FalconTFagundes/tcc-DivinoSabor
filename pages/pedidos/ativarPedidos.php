<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $idPedido = $dados['id'];
    $ativarPedido = $dados['a'];
    
    if($ativarPedido == 'A'){
        $retorno = upUm('pedidos', 'ativo', 'idpedidos', 'A', "$idPedido");
    } else {
        $retorno = upUm('pedidos', 'ativo', 'idpedidos', 'D', "$idPedido");
    }
} else {
    echo json_encode('Ativar não concluido!! ');
}

echo json_encode($retorno);