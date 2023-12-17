<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

// include_once "../../config/constantes.php";
// include_once "../../config/conexao.php";
// include_once "../../func/dashboard.php";

$retorno = '';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();



if(!empty($dados) && isset($dados)) {

    $idingrediente = $dados['id'];
    $ativarIngrediente = $dados['a'];
    
    if($ativarIngrediente == 'A'){
        $retorno = upUm('ingredientes', 'ativo', 'idingredientes', 'A', "$idingrediente");
    } else {
        $retorno = upUm('ingredientes', 'ativo', 'idingredientes', 'D', "$idingrediente");
    }
} else {
    echo json_encode('Ativar não concluido!! ');
}

echo json_encode($retorno);