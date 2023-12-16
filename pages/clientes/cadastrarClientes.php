<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $nomeCliente = $dados['nomeCliente']; 
    $endereoCliente = $dados['enderecoCliente'];
    $complementoCliente = $dados['complementoCliente'];
    $cidadeCliente = $dados['cidadeCliente'];
    $estadoCliente = $dados['estadoCliente'];
    $cepCliente = $dados['cepCliente'];
    $telefoneCliente = $dados['telefoneCliente'];

    var_dump($dados);

    $dataeHoraAtual = date('Y-m-d H:i:s');

    $retornoInsert = insertOito('clientes','nome, endereco, complemento, cidade, estado, cep, telefone, cadastro',"$nomeCliente", "$endereoCliente", "$complementoCliente", "$cidadeCliente", "$estadoCliente", "$cepCliente", "$telefoneCliente", "$dataeHoraAtual"); /* função PHP que faz o insert  */
    echo json_encode($retornoInsert); /* envia o final da ação da função - (VERIFICA NA PÁGINA DE FUNÇÃO) $retornoInsert recebe 'Gravado' ou 'nGravado'  */

} else {
    echo json_encode('Erro no Insert');
} 

