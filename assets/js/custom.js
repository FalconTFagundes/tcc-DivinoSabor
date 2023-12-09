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

        // quando clica o evento aparece para poder agendar
        select: function (arg) {
            var title = prompt('Event Title:');
            if (title) {
                calendar.addEvent({
                    title: title,
                    start: arg.start,
                    end: arg.end,
                    allDay: arg.allDay
                });
            }
            calendar.unselect();
        },
        eventClick: function (arg) {
            if (confirm('Are you sure you want to delete this event?')) {
                arg.event.remove();
            }
        },

        // eu parei no passar o código para ao banco de dados no evento custom estatico

        // permite arrastar e redimensionar os eventos diretamente no calendário
        editable: true,

        // botão de mais links, número máximo de eventos em um determinado dia, se for true, o número de eventos será listado à altura da célula do dia
        dayMaxEvents: true,

        events: 'listar_evento_calendario.php'


    });

    calendar.render();
});
