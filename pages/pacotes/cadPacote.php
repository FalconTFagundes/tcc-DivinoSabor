<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $nomePacote = $dados['nomePacote']; 
    $quantitativoPacote = $dados['quantitativoPacote'];
    $nomeClientePacote = $dados['clientePacoteId'];

    var_dump($dados); 

    $dataeHoraAtual = date('Y-m-d H:i:s');

    $retornoInsert = insertQuatro('pacote','pacote, qtdPessoas, cadastro, idclientes',"$nomePacote", "$quantitativoPacote", "$dataeHoraAtual", "$nomeClientePacote"); /* função PHP que faz o insert  */
    echo json_encode($retornoInsert); /* envia o final da ação da função - (VERIFICA NA PÁGINA DE FUNÇÃO) $retornoInsert recebe 'Gravado' ou 'nGravado'  */

} else {
    echo json_encode('Erro no Insert');
} 