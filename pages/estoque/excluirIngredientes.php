<?php

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$dados_delete = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$idingred = $dados_delete['id'];

excluirDashboard('ingredientes','idingredientes', "$idingred");