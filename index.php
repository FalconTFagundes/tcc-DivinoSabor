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

    <!-- Bootstrap v4.0 CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <!-- CSS normal -->
    <link rel="stylesheet" href="./assets/css/style.css">


    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" async>


    <title>Divino Sabor | Dashboard</title>
</head>

<body>



    <!-- menu responsivo -->
    <div class="footer-father-f-f">

        <!-- tá tudo na página home -->
        <?php

        if (!empty($_SESSION['idUser'])) {
            include_once './home.php';
        } else {
            include_once './login/login.php';
        }


        ?>

    </div>

    <!-- jQuery -->
    <script src="assets/jQuery/jquery3.7.1.js"></script>

    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <!-- Bootstrap v4.0 -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- calendario -->
    <script src='./assets/js/index.global.min.js'></script>
    <script src='./assets/js/core/locales-all.global.min.js'></script>
    <script src='./assets/js/custom.js'></script>

    <!-- painel js -->
    <script src="./assets/js/painel.js"></script>

</body>

</html>