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

    <!-- faviicon icon net guia -->
    <link href="assets/images/favicon/favicon.ico" rel="icon">
    <link href="assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">


    <!-- Bootstrap v4.0 CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <!-- CSS normal -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- icons -->
    <link rel="stylesheet" href="assets/fontAwesome/css/all.min.css" /> <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/boxicons/css/boxicons.min.css"> <!-- Boxicons -->


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
    <script src="assets/popperJs/popper.js"></script>

    <!-- Bootstrap v4.0 -->
   <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- inputmask -->
    <script src="assets/inputMask/inputMask.js"></script> 

    <!-- sweet alert -->
   <script src="assets/sweetAlert2/sweetAlert2.js"></script>

    <!-- calendario -->
    <script src='./assets/js/index.global.min.js'></script>
    <script src='./assets/js/core/locales-all.global.min.js'></script>
    <script src='./assets/js/custom.js'></script>

    <!-- painel js -->
    <script src="./assets/js/painel.js"></script>

</body>

</html>