<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $nomePedido = $dados['nomePedido']; 
    $pedido = $dados['pediido'];
    $detalhesPedido = $dados['detalhesPedido'];
    $dataEntregaPedido = $dados['dataEntregaPedido'];

    $dataeHoraPedido = date('Y-m-d H:i:s');


    $retornoInsert = insertCinco('pedidos','nome, pedido, detalhes, dataEntrega, cadastro',"$nomePedido", "$statusPedido", "$detalhesPedido", "$dataEntregaPedido", "$dataeHoraPedido"); /* função PHP que faz o insert  */
    echo json_encode($retornoInsert); /* envia o final da ação da função - VERIFICA NA PÁGINA DE FUNÇÃO, $retornoInsert recebe 'Gravado' ou 'nGravado'  */

} else {
    echo json_encode('Erro no Insert');
} 

