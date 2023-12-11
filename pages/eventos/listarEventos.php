<style>
    #mostrarCalendarioBtn {
        position: absolute;
        top: 50%;
        right: 45%;
        transform: translateY(-50%);
        background-color: #3498db;
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
        background-color: #2c3e50;
    }
</style>
</head>

<body>

    <div class="calendario-body">
        <div id='calendar'></div>
    </div>

    <button id="mostrarCalendarioBtn" onclick="mostrarCalendario()">Mostrar Calendário</button>


    <div class="modal fade" id="modalCadEvento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:blueviolet; color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Evento</h5>
                </div>
                <form name="frmCadEvento" method="POST" id="frmCadEvento" class="frmCadEvento" action="#">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tituloEvento" class="form-label">Título do Evento</label>
                            <input type="text" class="form-control" name="tituloEvento" id="tituloEvento" aria-describedby="tituloEvento" required>
                        </div>
                        <div class="mb-3">
                            <label for="horaInicio" class="form-label">Início do Evento</label>
                            <input type="datetime-local" class="form-control maskDateTime" name="horaInicio" id="horaInicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="horaFim" class="form-label">Fim do Evento</label>
                            <input type="datetime-local" class="form-control maskDateTime" name="horaFim" id="horaFim" required>
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
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
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
        });
    </script>


</body>

</html>