<?php
include_once 'config/constantes.php';
include_once 'config/conexao.php';
include_once 'func/dashboard.php';


// Receber o código de barras
$codigoDeBarras = filter_input(INPUT_POST, 'codigoDeBarras', FILTER_SANITIZE_STRING);

// Inicializar um array para armazenar os dados da consulta
$resultados = array();

try {
    // Realizar a consulta no banco de dados com base no código de barras
    $conn = conectar();
    $query = "SELECT * FROM ingredientes WHERE codigo = :codigoDeBarras";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':codigoDeBarras', $codigoDeBarras, PDO::PARAM_INT);
    $stmt->execute();

    // Verificar se a consulta teve sucesso
    if ($stmt->rowCount() > 0) {
        // Obter os resultados da consulta
        $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Retornar os resultados em formato JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    // Se houver um erro, imprima o erro
    echo json_encode(array('error' => $e->getMessage()));
}
?>
