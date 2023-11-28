<?php

$acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

switch ($acao) {

        // ações da parte de usuarios 
    case 'listarPedidos':
        include_once './php/listarPedidos.php';
        break;



        // ações da parte de vendedores/artistas
    case 'ativarVendedor':
        include_once './vendedor/ativarProduto.php';
        break;


}
