</head>

<body>

    <div class="calendario-body">
        <div id='calendar'></div>
    </div>

    <button type="button" class="btn btn-primary btn-lg" id="mostrarCalendarioBtn" onclick="mostrarCalendario()"><i class="fa-solid fa-eye" title="Exibir"></i> Mostrar Calendário</button>

    <button type="button" class="btn btn-secondary btn-lg" id="btnGerarCalendario" onclick="mostrarAlerta()">
        <i class="fa-solid fa-print" title="Gerar Relatório"></i> Gerar Relatório Geral
    </button>
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
        function mostrarAlerta() {
            Swal.fire({
                title: 'Você tem certeza?',
                text: 'Deseja gerar o relatório geral dos eventos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, gerar relatório!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Gerando Relatório :D',
                    showConfirmButton: false,
                    timer: 700

                })
                    window.location.href = './gerarRelatorios/gerarRelatEventos.php';
                }
            });
        }


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