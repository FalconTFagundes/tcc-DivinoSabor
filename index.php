<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <?php

    include_once './config/constantes.php';
    include_once './config/conexao.php';
    include_once './func/dashboard.php';

    ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="./assets/css/style.css">


    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Divino Sabor | Dashboard</title>
</head>

<body>



    <!-- menu responsivo -->
    <div class="footer-father-f-f">

        <?php

        // Arquivo com o rodapé do site
        
        include_once './php/navbar.php';
        include_once './php/time.php';
        ?>

        <div class="footer-father-f">
            <div class="footer-father">

                <div class="container-fluid">
                    <div class="toggle" id="toggle" onclick="menu-expand()">
                        <i class="fa-solid fa-plus" id="plus"></i>
                    </div>
                    <div class="menu" id="menu">
                        <a href="">
                            <i class="fa-solid fa-list"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                        </a>
                        <a href="">
                            <i class="fas fa-warehouse"></i>
                        </a>
                        <a href="">
                            <i class="fa-solid fa-chart-column"></i>
                        </a>
                    </div>
                    <div id="showpage">

            <?php

             include_once './index.php';

            ?>

        </div>
                </div>

                


            </div>
        </div>

        <?php

                // Arquivo com o rodapé do site
                include_once './php/footer.php';

                ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./assets/js/painel.js"></script>

</body>

</html>