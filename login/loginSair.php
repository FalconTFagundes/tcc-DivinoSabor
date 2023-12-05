<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

unset($_SESSION['idUser']);
unset($_SESSION['nomeUser']);

echo json_encode('OK');
die();
