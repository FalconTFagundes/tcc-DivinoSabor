<div class="container">
    <div id="clock" class="clock <?php
                    if (!empty($_SESSION['page'])) {
                        echo 'clocka';
                    }
                    ?>">
        <div id="controle-clock-toggle" class="">
            <div id="Datea" class="clock-time">
                  
            </div>
            <div class="horas">
                <div id="hoursa" class="clock-time"></div>
                <div id="space" class="clock-time">:</div>
                <div id="mina" class="clock-time"></div>
            </div>
        </div>
    </div>
</div>