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
	$query = "SELECT * FROM clientes WHERE nome LIKE '%$busca%' ORDER BY nome";
} else {
	$query = "SELECT * FROM clientes ORDER BY nome";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if ($rowCount > 0) {
	foreach ($result as $row) {
		echo '
            <tr>
                <td>' . $row["idclientes"] . '</td>
                <td>' . $row["nome"] . '</td>
                <td>' . $row["endereco"] . '</td>
                <td>' . $row["complemento"] . '</td>
                <td>' . $row["estado"] . '</td>
                <td>' . $row["cidade"] . '</td>
                <td>' . $row["telefone"] . '</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">';


		if ($row["ativo"] == 'A') {
			echo '
                        <button type="button" class="btn btn-outline-success" onclick="ativarGeral(' . $row["idclientes"] . ',\'desativar\',\'ativarClientes\',\'listarClientes\', \'Cliente Desativado com Sucesso\');">
                            <i class="fa-solid fa-unlock" title="Cliente Ativado"></i> Ativado
                        </button>
            ';
		} else {
			echo '
                        <button type="button" class="btn btn-outline-warning" onclick="ativarGeral(' . $row["idclientes"] . ', \'ativar\', \'ativarClientes\',\'listarClientes\', \'Cliente Ativado com Sucesso\');">
                            <i class="fa-solid fa-lock" title="Cliente Não Ativado"></i> Desativado
                        </button>
            ';
		}

		echo '
                        <a href="#" onclick="mostrarAlertaIdGet(' . $row["idclientes"] . ')">
                            <button type="button" class="btn btn-outline-info">
                                <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                            </button>
                        </a>
                        <button type="submit" class="btn btn-outline-danger" onclick="excGeral(' . $row["idclientes"] . ', \'excluirClientes\', \'listarClientes\', \'Certeza que deseja excluir este Cliente?\', \'Operação Irreversível!\')">
                            <i class="fa-solid fa-trash" title="Excluir"></i> Excluir
                        </button>
                    </div>
                </td>
            </tr>
        ';
	}
} else {
	echo "<tr><td colspan='8'>Nenhum registro localizado.</td></tr>";
}
