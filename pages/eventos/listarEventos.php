<style>
    #mostrarCalendarioBtn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #9E77F1;
        color: #ffffff;
        padding: 15px 30px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    #mostrarCalendarioBtn:hover {
        background-color: #483D8B;
    }

    #btnGerarCalendario {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #9E77F1;
        color: #ffffff;
        padding: 15px 30px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    #btnGerarCalendario:hover {
        background-color: #483D8B;
    }
</style>

</head>

<body>

    <div class="calendario-body">
        <div id='calendar'></div>
    </div>

    <button type="button" class="btn btn-primary btn-lg" id="mostrarCalendarioBtn" onclick="mostrarCalendario()"><i class="fa-solid fa-eye" title="Exibir"></i> Mostrar Calendário</button>

    <a href="./gerarRelatorios/gerarRelatEventos.php">
        <button type="button" class="btn btn-secondary btn-lg" id="btnGerarCalendario"><i class="fa-solid fa-print" title="Gerar Relatório"></i> Gerar Relatório Geral</button>
    </a>
    <br><br>



    <!-- Modal Cad Evento -->
    <div class="modal fade" id="modalCadEvento" tabindex="-1" role="dialog" aria-labelledby="modalCadPedido" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:blueviolet; color: white; ">
                    <h5 class="modal-title" id="modalCadEvento">Cadastrar Evento <i class="fa-regular fa-calendar-check" title="Cadastro de Eventos"></i></h5>
                </div>
                <form name="frmCadEvento" method="POST" id="frmCadEvento" class="frmCadEvento" action="#">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tituloEvento" class="form-label">Título do Evento</label>
                            <input type="text" class="form-control" name="tituloEvento" id="tituloEvento" aria-describedby="tituloEvento" required>
                        </div>
                        <div class="form-group">
                            <label for="horaInicio" class="form-label">Início do Evento</label>
                            <input type="datetime-local" class="form-control" name="horaInicio" id="horaInicio" required>
                        </div>
                        <div class="form-group">
                            <label for="horaFim" class="form-label">Fim do Evento</label>
                            <input type="datetime-local" class="form-control" name="horaFim" id="horaFim" required>
                        </div>
                        <div class="mb-3">
                            <label for="horaFim" class="form-label">Selecione a Cor</label>
                            <select class="custom-select" name="corEvento">
                                <option selected value="#9E77F1" style="color: #9E77F1;">Roxo</option>
                                <option value="#D4C200" style="color: #D4C200;">Amarelo</option>
                                <option value="#297BFF" style="color: #297BFF;">Azul</option>
                                <option value="#FF0831" style="color: #FF0831;">Vermelho</option>
                                <option value="#00BD3f" style="color: #00BD3f;">Verde</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check" title="Cadastrar Evento"></i> Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarCalendario() {
            msgGeral('Carregando Calendário', 'success');

            // Recarrega a página após uns milésimos aí - 800
            setTimeout(() => {
                // Redireciona para index.php
                window.location.href = 'index.php';
            }, 800);
        }

        // Oculta o botão após a página ser carregada
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('mostrarCalendarioBtn').style.display = 'none';
            document.getElementById('btnGerarCalendario').style.display = 'none';

        });
    </script>




</body>

</html>