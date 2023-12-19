<?php
include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

try {
    // Obtém a conexão
    $conn = conectar();

    // QUERY para recuperar os eventos da tabela 'events'
    $query_events = "SELECT id, title, color, DATE_FORMAT(start, '%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(end, '%Y-%m-%dT%H:%i:%s') AS end FROM events";

    // Prepara o QUERY
    $result_events = $conn->prepare($query_events);

    // Executa o QUERY
    $result_events->execute();

    // Criar o array que recebe os eventos
    $eventos = [];

    // Percorre a lista de registros retornados do banco de dados para a tabela 'events'
    while ($row_events = $result_events->fetch(PDO::FETCH_ASSOC)) {
        // Extrai o array
        extract($row_events);

        $eventos[] = [
            'id' => $id,
            'title' => $title,
            'color' => $color,
            'start' => $start,
            'end' => $end,
        ];
    }

    // QUERY para recuperar os eventos da tabela 'pedidos'
    $query_pedidos = "SELECT idpedidos as id, pedido as title, cor_pedidos as color, DATE_FORMAT(cadastro, '%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(dataEntrega, '%Y-%m-%dT%H:%i:%s') AS end FROM pedidos";

    // Prepara o QUERY
    $result_pedidos = $conn->prepare($query_pedidos);

    // Executa o QUERY
    $result_pedidos->execute();

    // Percorre a lista de registros retornados do banco de dados para a tabela 'pedidos'
    while ($row_pedidos = $result_pedidos->fetch(PDO::FETCH_ASSOC)) {
        // Extrai o array
        extract($row_pedidos);

        $eventos[] = [
            'id' => $id,
            'title' => $title,
            'color' => $color,
            'start' => $start,
            'end' => $end,
            'tipo' => 'pedido'
        ];
    }

    // Configurar cabeçalho
    header('Content-Type: application/json');

    // Saída JSON
    echo json_encode($eventos);
} catch (Exception $e) {
    // Verificação de erro >.<
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Erro ao recuperar eventos: ' . $e->getMessage()]);
}
?>
