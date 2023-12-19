<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $clientePedidoId = $dados['clientePedidoId']; 
    $pedido = $dados['pedido'];
    $detalhesPedido = $dados['detalhesPedido'];
    $dataEntregaPedido = $dados['dataEntregaPedido'];
    $corPedidoCalendario = $dados['corPedidoCalendario'];

    $dataeHoraPedido = date('Y-m-d H:i:s');


    $retornoInsert = insertSeis('pedidos','idclientes, pedido, detalhes, dataEntrega, cadastro, cor_pedidos',"$clientePedidoId", "$pedido", "$detalhesPedido", "$dataEntregaPedido", "$dataeHoraPedido", "$corPedidoCalendario"); /* função PHP que faz o insert  */
    echo json_encode($retornoInsert); /* envia o final da ação da função - VERIFICA NA PÁGINA DE FUNÇÃO, $retornoInsert recebe 'Gravado' ou 'nGravado'  */

} else {
    echo json_encode('Erro no Insert');
} 

