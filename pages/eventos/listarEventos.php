
    <style>
    
        #mostrarCalendarioBtn {
            position: absolute;
            top: 50%;
            right: 45%;
            transform: translateY(-50%);
            background-color: #3498db; /* Cor de fundo do botão */
            color: #ffffff; /* Cor do texto do botão */
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Transição de cor ao passar o mouse */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Sombra leve */
        }

        #mostrarCalendarioBtn:hover {
            background-color: #2c3e50; /* Cor de fundo do botão ao passar o mouse */
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
    // Aqui, você pode adicionar lógica adicional, se necessário,
    // antes de recarregar a página

    // Recarrega a página
    location.reload();
}

// Oculta o botão após a página ser carregada
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('mostrarCalendarioBtn').style.display = 'none';
});
</script>

</body>
</html>
