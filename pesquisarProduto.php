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
    $query = "SELECT * FROM produtos WHERE produto LIKE '%$busca%' ORDER BY produto";
} else {
    $query = "SELECT * FROM produtos ORDER BY produto";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if ($rowCount > 0) {
    foreach ($result as $row) {
        echo '
            <tr>
                <td>' . $row["idprodutos"] . '</td>
                <td><img src="./assets/images/produtos/' . $row["img"] . '" alt="Imagem Produto" class="img-thumbnail"></td>
                <td>' . $row["produto"] . '</td>
                <td>' . $row["valor"] . '</td>
                <td>' . $row["cadastro"] . '</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">';

        if ($row["ativo"] == 'A') {
            echo '
                        <button type="button" class="btn btn-outline-success" onclick="ativarGeral(' . $row["idprodutos"] . ',\'desativar\',\'ativarProdutos\',\'listarProdutos\', \'Produto Desativado com Sucesso\');">
                            <i class="fa-solid fa-unlock" title="Produto Ativado"></i> Ativado
                        </button>
            ';
        } else {
            echo '
                        <button type="button" class="btn btn-outline-warning" onclick="ativarGeral(' . $row["idprodutos"] . ', \'ativar\', \'ativarProdutos\',\'listarProdutos\', \'Produto Ativado com Sucesso\');">
                            <i class="fa-solid fa-lock" title="Produto Não Ativado"></i> Desativado
                        </button>
            ';
        }

        echo '
                        <a href="#" onclick="mostrarAlertaIdGet(' . $row["idprodutos"] . ')">
                            <button type="button" class="btn btn-outline-info">
                                <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                            </button>
                        </a>
                        <button type="submit" class="btn btn-outline-danger" onclick="excGeral(' . $row["idprodutos"] . ', \'excluirProdutos\', \'listarProdutos\', \'Certeza que deseja excluir este Produto?\', \'Operação Irreversível!\')">
                            <i class="fa-solid fa-trash" title="Excluir"></i> Excluir
                        </button>
                    </div>
                </td>
            </tr>
        ';
    }
} else {
    echo "<tr><td colspan='6'><div class='alert alert-warning' role='alert'>
    Nenhum registro localizado.
   </div></td></tr>";
}
