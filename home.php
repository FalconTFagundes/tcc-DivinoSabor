<?php

// Arquivo com o rodapé do site

include_once './pages/navbar.php';
include_once './pages/time.php';
?>

<div class="footer-father-f">
    <div class="footer-father">

        <div class="container-fluid">

            <div id="controle-menu-toggle" class="
            <?php
            // fazer com que o menu permaneça de lado com a página carregada para layout
            if (!empty($_SESSION['page'])) {
                echo 'menu-lado';
            }
            ?>
            ">
                <div class="toggle" id="toggle" onclick="menu-expand()">
                    <i class="fa-solid fa-plus" id="plus"></i>
                </div>

                <div class="menu" id="menu">
                    <a href="#" class="linkMenu" idMenu="listarPedidos">
                        <i class="fa-solid fa-list-check" title="Pedidos"></i>
                    </a>

                    <a href="#" class="linkMenu" idMenu="listarFinanceiro">
                        <i class="fa-solid fa-money-bill-trend-up" title="Financeiro"></i>
                    </a>

                    <a href="#" class="linkMenu" idMenu="listarIngredientes">
                        <i class="fa-solid fa-boxes-stacked" title="Estoque"></i>
                    </a>

                    <a href="#" class="linkMenu" idMenu="listarEventos">
                        <i class="fa-solid fa-calendar-days" title="Eventos"></i>
                    </a>

                    <a href="#" class="linkMenu" idMenu="listarClientes">
                        <i class="fa-solid fa-user-plus" title="Clientes"></i>
                    </a>

                    <a href="#" class="linkMenu" idMenu="listarPacotes">
                        <i class="fa-solid fa-box-open" title="Pacotes"></i>
                    </a>
                    <!-- <a href="#" class="linkMenu" idMenu="listarProdutos">
                    <i class="fa-solid fa-cart-flatbed" title="Produtos"></i>
                    </a> -->

                    <!-- criar +2 novos botões no menu -->
                </div>
            </div>

            <div id="showpage">

                <?php

                // se existe, tem página mostrando
                // código para continuar na página para alterar layout
                if (!empty($_SESSION['page'])) {
                    $page = $_SESSION['page'];
                    if ($page == 'listarPedidos') {
                        include_once './pages/pedidos/listarPedidos.php';
                    }
                    if ($page == 'listarEventos') {
                        include_once './pages/eventos/listarEventos.php';
                    }
                    if ($page == 'listarClientes') {
                        include_once './pages/clientes/listarClientes.php';
                    }
                    if ($page == 'listarFinanceiro') {
                        include_once './pages/financeiro/listarFinanceiro.php';
                    }
                    if ($page == 'listarPacotes') {
                        include_once './pages/pacotes/listarPacotes.php';
                    }
                    if ($page == 'listarIngredientes') {
                        include_once './pages/ingredientes/listarIngredientes.php';
                    }
                    if ($page == 'perguntas') {
                        include_once './footerPages/perguntas.php';
                    }
                    if ($page == 'sobreNos') {
                        include_once './footerPages/sobreNos.php';
                    }
                    if ($page == 'suporte') {
                        include_once './footerPages/suporte.php';
                    }
                    if ($page == 'termos') {
                        include_once './footerPages/termos.php';
                    }
                }

                // if (!empty($_GET['page'])) {
                //     // faz com que se estiver verdadeiro, o menu vai para o lado com uma classe existente apenas para isso

                //     $_SESSION['menuDrag'] = 'Verdadeiro';

                //     if ($_GET['page'] == 'listarPedidos') {
                //         include_once './php/listarPedidos.php';
                //     }
                // } else {              
                //     $_SESSION['menuDrag'] = 'Falso';                                        
                // }
                ?>
            </div>
        </div>




    </div>
</div>

<?php

// Arquivo com o rodapé do site
include_once './pages/footer.php';


?>