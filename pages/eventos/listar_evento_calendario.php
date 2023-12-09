<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

// QUERY para recuperar os eventos
$query_events = "SELECT id, title, color, start, end FROM events";

// Prepara o QUERY
$result_events = $conn->prepare($query_events);

// Executa o QUERY
$result_events->execute();

// Criar o array que recebe os eventos
$eventos = [];

// Percorre a lista de registros retornados do banco de dados
while($row_events = $result_events->fetch(PDO::FETCH_ASSOC)){
    // Apenas adiciona o array associativo ao array de eventos
    $eventos[] = $row_events;
}

// Retorna os eventos no formato JSON
echo json_encode($eventos);

?>


