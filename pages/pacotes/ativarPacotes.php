<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $idPacoteCad = $dados['id'];
    $ativarPacoteCad = $dados['a'];
    
    if($ativarPacoteCad == 'A'){
        $retorno = upUm('pacotecadastro', 'ativo', 'idpacotecadastro', 'A', "$idPacoteCad");
    } else {
        $retorno = upUm('pacotecadastro', 'ativo', 'idpacotecadastro', 'D', "$idPacoteCad");
    }



    
} else {
    echo json_encode('Ativar não concluido!! ');
}

echo json_encode($retorno);