// executar quando o documento php estiver totalmente carregado


document.addEventListener('DOMContentLoaded', function () {

    // define a data atual
    const today = date.getDate();

    // receber o seletor calendar do atributo ID
    var calendarEl = document.getElementById('calendar');

    // instanciar o FullCalendar.calendar e atribuir a variável calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {


        //   cria o cabeçalho do calendário
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // define o idioma usado no calendário no arquivo
        locale: 'pt-br',

        // define a data atual
        //   initialDate: '2023-01-12',        
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
                })
            }
            calendar.unselect()
        },
        eventClick: function (arg) {
           /*  if (confirm('Are you sure you want to delete this event?')) {
                arg.event.remove()
            } */
        },


        // permite arrastar e redimensionar os eventos diretamente no calendário
        editable: true,

        // botão de mais links, número máximo de eventos em um determinado dia, se for true, o número de eventos será listado à altura da célula do dia
        dayMaxEvents: true, 

        
        events: [
            {
                title: 'All Day Event',
                start: '2023-01-01'
            },
            {
                title: 'Long Event',
                start: '2023-01-07',
                end: '2023-01-10'
            },
            {
                groupId: 999,
                title: 'Repeating Event',
                start: '2023-01-09T16:00:00'
            },
            {
                groupId: 999,
                title: 'Repeating Event',
                start: '2023-01-16T16:00:00'
            },
            {
                title: 'Conference',
                start: '2023-01-11',
                end: '2023-01-13'
            },
            {
                title: 'Meeting',
                start: '2023-01-12T10:30:00',
                end: '2023-01-12T12:30:00'
            },
            {
                title: 'Lunch',
                start: '2023-01-12T12:00:00'
            },
            {
                title: 'Meeting',
                start: '2023-01-12T14:30:00'
            },
            {
                title: 'Happy Hour',
                start: '2023-01-12T17:30:00'
            },
            {
                title: 'Dinner',
                start: '2023-01-12T20:00:00'
            },
            {
                title: 'Birthday Party',
                start: '2023-01-13T07:00:00'
            },
            {
                title: 'Click for Google',
                url: 'http://google.com/',
                start: '2023-01-28'
            }
        ]
    });

    calendar.render();
});
