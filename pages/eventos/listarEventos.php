
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
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);        }

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

<script>
function mostrarCalendario() {
    msgGeral('Carregando Calendário', 'success');

    // Recarrega a página após uns milésimos aí - 800
    setTimeout(() => {
        location.reload();
}, "800");
   
}

// Oculta o botão após a página ser carregada
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('mostrarCalendarioBtn').style.display = 'none';
});
</script>

</body>
</html>
