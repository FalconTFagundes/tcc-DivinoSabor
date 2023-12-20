<?php
include_once 'config/constantes.php';
include_once 'config/conexao.php';
include_once 'func/dashboard.php';

// Obtém a conexão
$connect = conectar();

// Verifica se a conexão está estabelecida
if (!$connect) {
    die("Erro de conexão com o banco de dados: " . mysqli_connect_error());
}


if (isset($_POST["nome"])) {
    $busca = $_POST["nome"];
    // Use $nomeClientePedido in your query if needed
    $query = "SELECT pedidos.*, clientes.nome as nomeCliente
    FROM pedidos
    INNER JOIN clientes ON pedidos.idclientes = clientes.idclientes
    WHERE pedidos.pedido LIKE '%$busca%'
    ORDER BY pedidos.pedido;
    ";
} else {
    $query = "SELECT pedidos.*, clientes.nome as nomeCliente
    FROM pedidos
    INNER JOIN clientes ON pedidos.idclientes = clientes.idclientes
    ORDER BY pedidos.pedido;
    ";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if ($rowCount > 0) {
    foreach ($result as $row) {
        echo '<tr>
                <td>' . $row["idpedidos"] . '</td>
                <td>' . $row["nomeCliente"] . '</td>
                <td>' . $row["pedido"] . '</td>
                <td>' . $row["detalhes"] . '</td>
                <td>' . $row["dataEntrega"] . '</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">';
        if ($row["ativo"] == 'A') {
            echo '<button type="button" class="btn btn-outline-secondary" onclick="ativarGeral(' . $row["idpedidos"] . ',\'desativar\',\'ativarPedidos\',\'listarPedidos\', \'Pedido marcado como concluído\');">
                        <i class="fa-solid fa-unlock" title="Pedido Não Concluído"></i> Em andamento
                    </button>';
        } else {
            echo '<button type="button" class="btn btn-outline-success" onclick="ativarGeral(' . $row["idpedidos"] . ', \'ativar\', \'ativarPedidos\',\'listarPedidos\', \'Pedido marcado como não concluído\');">
                        <i class="fa-solid fa-lock" title="Pedido Concluído"></i> Concluído
                    </button>';
        }
        echo '<a href="#" onclick="mostrarAlertaIdGet(' . $row["idpedidos"] . ')">
                    <button type="button" class="btn btn-outline-info btnGerarRelatPedidoUn">
                        <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                    </button>
                </a>
                <button type="button" class="btn btn-outline-danger" onclick="excGeral(' . $row["idpedidos"] . ', \'excluirPedidos\', \'listarPedidos\', \'Certeza que deseja excluir?\', \'Operação Irreversível!\')">
                    <i class="fa-solid fa-trash" title="Excluir"></i> Excluir
                </button>
            </div>
        </td>
    </tr>';
    }
} else {
    echo "<tr><td colspan='6'><div class='alert alert-warning' role='alert'>
    Nenhum registro localizado.
   </div></td></tr>";
}
