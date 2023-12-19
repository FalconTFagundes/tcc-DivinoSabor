<!-- a class do calendário é apenas para aproveitamento da estilização -->

<button type="button" class="btn btn-primary btn-lg linkMenuEstoqueDecision" idMenu="listarIngredientes" id="mostrarCalendarioBtn"><i class="fa-solid fa-eye" title="Exibir"></i> Exibir Ingredientes</button>

<button type="button" class="btn btn-secondary btn-lg linkMenuEstoqueDecision" idMenu="listarProdutos" id="btnGerarCalendario"><i class="fa-solid fa-layer-group"></i> Exibir Produtos</button>

<script>
    $('.linkMenuEstoqueDecision').click(function(event) {
        event.preventDefault();

        let menuClicado = $(this).attr('idMenu');

        let dados = {
            acao: menuClicado,
        };

        console.log(dados);

        var menuToggle = document.getElementById('controle-menu-toggle');
        var clockToggle = document.getElementById('controle-clock-toggle');
        var clockNavToggle = document.getElementById('clock-nav');

        $.ajax({
            type: "POST",
            dataType: 'html',
            url: 'controle.php',
            data: dados,
            beforeSend: function() {
                // loading();
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Redirecionando para a página :D",
                    showConfirmButton: false,
                    timer: 1000,
                });
            },
            success: function(retorno) {
                // Exibe a mensagem de sucesso por 1 segundo antes de redirecionar
                setTimeout(function() {
                    if (retorno !== 'Home') {
                        // loadingEnd();
                        $('div#showpage').html(retorno);
                        document.getElementById("clock").classList.remove("clock");
                        document.getElementById("clock").classList.add("clock-time");

                        if (!menuToggle.classList.contains("menu-lado")) {
                            menuToggle.classList.toggle("menu-lado");
                        }

                        if (!clockToggle.classList.contains("clocka")) {
                            clockToggle.classList.toggle("clocka");
                        }

                        if (!clockNavToggle.classList.contains("clock-son")) {
                            clockNavToggle.classList.toggle("clock-son");
                        }
                    } else {
                        window.location.reload();
                    }
                }, 1000);
            },
            error: function(xhr, status, error) {
                // Trate os erros da solicitação AJAX aqui
                console.error(xhr.responseText);
                msgGeral('ERRO: ' + status + ' ' + error + ' Tente novamente mais tarde.', 'error');
            }
        });
    });
</script>