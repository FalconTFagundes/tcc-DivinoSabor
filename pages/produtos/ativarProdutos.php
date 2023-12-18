<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $idProduto = $dados['id'];
    $ativarProduto = $dados['a'];
    
    if($ativarProduto == 'A'){
        $retorno = upUm('produtos', 'ativo', 'idprodutos', 'A', "$idProduto");
    } else {
        $retorno = upUm('produtos', 'ativo', 'idprodutos', 'D', "$idProduto");
    }
} else {
    echo json_encode('Ativar não concluido!! ');
}

echo json_encode($retorno);