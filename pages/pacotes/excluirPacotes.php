<?php 

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados_delete = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$idPacote = $dados_delete['id'];

excluirDashboard('pacotecadastro','idpacotecadastro', "$idPacote");