<div class="divNav">
    <!-- <div class="divNavLogo">
        
    </div> -->
    <div class="menu-f">
        <ul>
            <li class="active">
                <a href="index.php" class="linkMenu" idMenu="Home">
                    <img src="./assets/images/favicon/logo-2015.png" alt="Logo Divino Sabor" style="width: 98px; height: 98px; flex-shrink: 0;">
                </a>
            </li>
        </ul>
    </div>
    <div class="navbar">
        <ul>
            <li>
                <!-- <a href="#" class="linkMenu" idMenu="Notificacoes">
                    <i class='bx bx-bell icon'></i>
                    <i class="bx bx-bell activeIcon" style="color: #caf177;"></i>
                </a> -->
            </li>
            <li class="dropdown">
                <a href="#" class="linkMenu" id="nomeUserNav" idMenu="Perfil">
                    <?php
                    echo $_SESSION['nomeUser'];
                    ?>

                    <!-- <i class='bx bx-user icon'></i>
                    <i class="bx bx-user activeIcon" style="color: #caf177;"></i> -->

                </a>


                <ul>
                    <li><a href="" onclick="LoginSair();">Sair</a></li>
                </ul>
            </li>
            <!-- <li>
                <a href="#" class="#">

                </a>
            </li> -->


            </li>

            <div class="algo">
                <div class="indicator"></div>
            </div>

            <!-- <div class="indicator"></div> -->
        </ul>
    </div>
    <div class="container" id="divDarkModeBtn">
        <button id="btn-dark-mode-toggle"></button>
    </div>
    <div class="menu-f">
        <div id="clock-nav" class="<?php
                                    // fazer com que o menu permaneça de lado com a página carregada para layout
                                    if (!empty($_SESSION['page'])) {
                                        echo 'clock-son';
                                    } else {
                                        echo 'clocka';
                                    }
                                    ?>">
            <div id="Date" class="clock-time">


            </div>
            <div class="horas">
                <div id="hours" class="clock-time"></div>
                <div id="space" class="clock-time">:</div>
                <div id="min" class="clock-time"></div>
            </div>
        </div>

    </div>
</div>