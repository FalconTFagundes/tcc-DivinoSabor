// executar quando o documento estiver totalmente carregado
document.addEventListener('DOMContentLoaded', function () {

    // define a data atual corretamente
    const today = new Date();

    // receber o seletor calendar do atributo ID
    var calendarEl = document.getElementById('calendar');

    // instanciar o FullCalendar.calendar e atribuir a variável calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        // cria o cabeçalho do calendário
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // define o idioma usado no calendário no arquivo
        locale: 'pt-br',

        // define a data atual corretamente
        initialDate: today,

        // permite clicar nos nomes dos dias e da semana
        navLinks: true,

        // permite clicar e arrastar o mouse sobre um ou vários dias no calendário
        selectable: true,

        // indicar visualmente a área que será selecionada antes que o usuário solte o botão do mouse para confirmar a seleção
        selectMirror: true,


        // Captura o dia clicado
        select: function cadCalendario(info) {
            // Obtém o objeto Date de info.start e info.end
            var inicio = new Date(info.start);
            var fim = new Date(info.end);

            // Formata as datas no padrão desejado (Y/m/d H:i:s)
            var formatoInicio = inicio.toISOString().slice(0, 19).replace("T", " ");
            var formatoFim = fim.toISOString().slice(0, 19).replace("T", " ");

            // Define os valores nos campos
            $('#modalCadEvento #horaInicio').val(formatoInicio);
            $('#modalCadEvento #horaFim').val(formatoFim);
            $('#modalCadEvento').modal('show');

            // Adiciona um evento de submissão ao formulário
            $('#frmCadEvento').submit(function (event) {
                event.preventDefault();  // Impede o comportamento padrão de submissão

                var formdata = $(this).serializeArray();
                formdata.push({
                    name: "acao",
                    value: "cadastrarEventos"
                });

                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url: 'controle.php',
                    data: formdata,
                    beforeSend: function (retorno) {
                        // Pode adicionar indicadores de carregamento ou outros feedbacks visuais aqui
                    },
                    success: function (retorno) {
                        $('#modalCadEvento').modal('hide');
                        console.log(retorno);
                        console.log(formatoInicio); // verificando como as datas estão sendo recebidassssssss
                        console.log(formatoFim);
                        setTimeout(function () {
                            atualizarPagina('listarEventos');
                        }, 1000);

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Salvo com Sucesso',
                            showConfirmButton: false,
                            timer: 1500

                        })

                        
                    }
                });
            });
        },





        /*     select: function cadCalendario(formId, modalId, pageAcao, pageRetorno) {
                
                this.on('submit', function (k) {
                    k.preventDefault();
                    var formdata = $(this).serializeArray();
                    formdata.push({
                        name: "acao",
                        value: pageAcao,
                        dadosDia: dadosDia
                    });
    
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url: 'controle.php',
                        data: formdata,
                        beforeSend: function (retorno) {
                        },
                        success: function (retorno) {
                            console.log(retorno);
                            $('#' + modalId).modal('hide');
                            setTimeout(function () {
                                atualizarPagina(pageRetorno);
                            }, 1000);
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Salvo com Sucesso',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                });
    
                calendar.unselect();
            }, */

        eventClick: function (arg) {
            var eventId = arg.event.id;
            Swal.fire({
                title: 'Deseja excluir este item?',
                text: 'Esta operação é irreversível!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {

                    var dados = {
                        acao: 'excluirEventos',
                        id: eventId
                    }

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Cancelado',
                        'Operação Cancelada',
                        'error'
                    )
                }

                $.ajax({
                    type: 'POST',
                    dataType: 'HTML',
                    url: 'controle.php',
                    data: dados,
                    beforeSend: function (retorno) {
                    }, success: function (retorno) {

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Salvo com Sucesso',
                            showConfirmButton: false,
                            timer: 700

                        })
                        setTimeout(function () {
                            atualizarPagina('listarEventos');
                        }, 1000)
                    }
                    
                    


                });
            })
        },

        // eu parei no passar o código para ao banco de dados no evento custom estatico

        // permite arrastar e redimensionar os eventos diretamente no calendário
        editable: true,

        // botão de mais links, número máximo de eventos em um determinado dia, se for true, o número de eventos será listado à altura da célula do dia
        dayMaxEvents: true,

        events: 'listar_evento_calendario.php',



    });


        calendar.render();
});

