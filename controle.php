<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

$acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

switch ($acao) {

    case 'Home':
        unset($_SESSION['page']);
        // echo 'Home';
        break;

        // ações da parte de usuarios 
    case 'listarPedidos':
        // chamar variável
        $_SESSION["page"] = 'listarPedidos';
        include_once './pages/pedidos/listarPedidos.php';
        break;

    case 'listarEventos':
        // chamar variável
        $_SESSION["page"] = 'listarEventos';
        include_once './pages/eventos/listarEventos.php';
        break;



        // páginas de ações

    case 'loginEntrar':
        // chamar variável
        // echo json_encode('entrou no controle login Entrar');
        include_once './login/loginEntrar.php';
        break;

    case 'loginSair':
        include_once './login/loginSair.php';
        break;


    case 'cadastrarPedidos':
        include_once './pages/pedidos/cadastrarPedidos.php';
        break;
        
}
