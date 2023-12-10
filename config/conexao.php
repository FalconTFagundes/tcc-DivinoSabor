<?php
function conectar() {
    try {
        $conn = new PDO('mysql:host=' . HOST . ';charset=utf8mb4;dbname=' . DBNAME, USER, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        // Retorna a conexão
        return $conn;
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        // Se houver um erro, pode ser uma boa ideia lançar a exceção novamente
        // para que a chamada da função receba a mensagem de erro.
        throw $e;
    }
}
