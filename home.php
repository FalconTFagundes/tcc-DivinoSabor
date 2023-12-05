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
                        <i class="fa-solid fa-list-check"></i>
                    </a>
                    <a href="#" class="linkMenu" idMenu="listarFinanceiro">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                    </a>
                    <a href="#" class="linkMenu" idMenu="listarEstoque">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </a>
                    <a href="#" class="linkMenu" idMenu="listarEventos">
                        <i class="fa-solid fa-calendar-days"></i>
                    </a>
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
                        header("Refresh:0; url=index.php");
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


