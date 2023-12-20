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
    $query = "SELECT * FROM ingredientes WHERE nomeIngred LIKE '%$busca%' ORDER BY nomeIngred";
} else {
    $query = "SELECT * FROM ingredientes ORDER BY nomeIngred";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if ($rowCount > 0) {
    foreach ($result as $row) {
        echo '
            <tr>
                <td>' . $row["idingredientes"] . '</td>
                <td><img src="./assets/images/ingredientes/' . $row["img"] . '" alt="Imagem Ingrediente" class="img-thumbnail"></td>

                <td>' . $row["nomeIngred"] . '</td>
                <td>' . $row["quantIngred"] . '</td>
                <td>' . $row["pesoUnit"] . '</td>
                <td>' . $row["precoUnit"] . '</td>
                <td>' . $row["dataComp"] . '</td>
                <td>' . $row["precoTotal"] . '</td>
                <td>' . $row["dataValidad"] . '</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">';


        if ($row["ativo"] == 'A') {
            echo '
                        <button type="button" class="btn btn-outline-success" onclick="ativarGeral(' . $row["idingredientes"] . ',\'desativar\',\'ativarIngredientes\',\'listarIngredientes\', \'Uso suspenso\');">
                            <i class="fa-solid fa-unlock" title="Ingrediente em Uso"></i> Em uso
                        </button>
            ';
        } else {
            echo '
                        <button type="button" class="btn btn-outline-warning" onclick="ativarGeral(' . $row["idingredientes"] . ', \'ativar\', \'ativarIngredientes\',\'listarIngredientes\', \'Ingrediente em uso\');">
                            <i class="fa-solid fa-lock" title="Ingrediente em desuso"></i> Uso suspenso
                        </button>
            ';
        }

        echo '
                        <a href="#" onclick="mostrarAlertaIdGet(' . $row["idingredientes"] . ')">
                            <button type="button" class="btn btn-outline-info">
                                <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                            </button>
                        </a>
                        <button type="submit" class="btn btn-outline-danger" onclick="excGeral(' . $row["idingredientes"] . ', \'excluirIngredientes\', \'listarIngredientes\', \'CCerteza que deseja excluir?\', \'Operação Irreversível!\')">
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
