<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

$acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

switch ($acao) {

    case 'Home':
        unset($_SESSION['page']);
        echo 'Home';
        break;

        // ações da parte de usuarios 
    case 'sobreNos':
        $_SESSION["page"] = 'sobreNos';
        include_once './footerPages/sobreNos.php';
        break;

    case 'perguntas':
        $_SESSION["page"] = 'perguntas';
        include_once './footerPages/perguntas.php';
        break;

    case 'suporte':
        $_SESSION["page"] = 'suporte';
        include_once './footerPages/suporte.php';
        break;

    case 'termos':
        $_SESSION["page"] = 'termos';
        include_once './footerPages/termos.php';
        break;



        // CLIENTES

    case 'listarClientes':
        $_SESSION["page"] = 'listarClientes';
        include_once './pages/clientes/listarClientes.php';
        break;

    case 'cadastrarClientes':
        include_once './pages/clientes/cadastrarClientes.php';
        break;

    case 'excluirClientes':
        include_once './pages/clientes/excluirClientes.php';
        break;

    case 'ativarClientes':
        include_once './pages/clientes/ativarClientes.php';
        break;


        // PEDIDOS

    case 'listarPedidos':
        // chamar variável
        $_SESSION["page"] = 'listarPedidos';
        include_once './pages/pedidos/listarPedidos.php';
        break;

    case 'cadastrarPedidos':
        include_once './pages/pedidos/cadastrarPedidos.php';
        break;

    case 'excluirPedidos':
        include_once './pages/pedidos/excluirPedidos.php';
        break;

    case 'ativarPedidos':
        include_once './pages/pedidos/ativarPedidos.php';
        break;


        // EVENTOS

    case 'listarEventos':
        // chamar variável
        $_SESSION["page"] = 'listarEventos';
        include_once './pages/eventos/listarEventos.php';
        break;

    case 'cadastrarEventos':
        include_once './pages/eventos/cadastrarEventos.php';
        break;

    case 'excluirEventos':
        include_once './pages/eventos/excluirEventos.php';
        break;


        // FINANCEIRO

    case 'listarFinanceiro':
        $_SESSION["page"] = 'listarFinanceiro';
        include_once './pages/financeiro/listarFinanceiro.php';
        break;


        // PACOTES

    case 'listarPacotes':
        $_SESSION["page"] = 'listarPacotes';
        include_once './pages/pacotes/listarPacotes.php';
        break;

    case 'cadPacote':
        include_once './pages/pacotes/cadPacote.php';
        break;

    case 'ativarPacotes':
        include_once './pages/pacotes/ativarPacotes.php';
        break;

    case 'excluirPacotes':
        include_once './pages/pacotes/excluirPacotes.php';
        break;

    case 'cadPacoteVenda':
        include_once './pages/pacotes/cadPacoteVenda.php';
        break;


        // ESTOQUE - DECISÃO (INGREDIENTE OU PRODUTO?)

    case 'estoqueDecision':
        $_SESSION["page"] = 'estoqueDecision';
        include_once './pages/estoqueDecision.php';
        break;

        // ESTOQUE - INGREDIENTES

    case 'listarIngredientes':
        $_SESSION["page"] = 'listarIngredientes';
        include_once './pages/ingredientes/listarIngredientes.php';
        break;

    case 'excluirIngredientes':
        include_once './pages/ingredientes/excluirIngredientes.php';
        break;

    case 'ativarIngredientes':
        include_once './pages/ingredientes/ativarIngredientes.php';
        break;

        // ESTOQUE - PRODUTOS

    case 'listarProdutos':
        $_SESSION["page"] = 'listarProdutos';
        include_once './pages/produtos/listarProdutos.php';
        break;

    case 'ativarProdutos':
        include_once './pages/produtos/ativarProdutos.php';
        break;

    case 'excluirProdutos':
        include_once './pages/produtos/excluirProdutos.php';
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
}
