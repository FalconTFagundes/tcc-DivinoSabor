<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if(!empty($dados) && isset($dados)) {

    $tituloEvento = $dados['tituloEvento']; 
    $horaInicioEvento = $dados['horaInicio'];
    $horaFimEvento = $dados['horaFim'];
    $corEvento = $dados['corEvento'];

    var_dump($dados);


    $retornoInsert = insertQuatro('events','title, color, start, end',"$tituloEvento", "$corEvento", "$horaInicioEvento", "$horaFimEvento"); /* função PHP que faz o insert  */
    echo json_encode($retornoInsert); /* envia o final da ação da função - (VERIFICA NA PÁGINA DE FUNÇÃO) $retornoInsert recebe 'Gravado' ou 'nGravado'  */

} else {
    echo json_encode('Erro no Insert');
} 

