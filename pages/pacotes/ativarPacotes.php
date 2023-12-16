<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $idPacote = $dados['id'];
    $ativarPacote = $dados['a'];
    
    if($ativarPacote == 'A'){
        $retorno = upUm('pacote', 'ativo', 'idpacote', 'A', "$idPacote");
    } else {
        $retorno = upUm('pacote', 'ativo', 'idpacote', 'D', "$idPacote");
    }



    
} else {
    echo json_encode('Ativar não concluido!! ');
}

echo json_encode($retorno);