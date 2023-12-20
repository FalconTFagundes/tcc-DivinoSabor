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
    $query = "SELECT
        pacote.pacote,
        MAX(pacote.idpacote) AS idpacote,
        MAX(pacote.qtdPessoas) AS qtdPessoas,
        MAX(pacotecadastro.valorPacote) AS valorPacote,
        MAX(pacotecadastro.detalhes) AS detalhes,
        MAX(pacote.ativo) AS AtivoPacoteCadastro,
        MAX(pacotecadastro.cadastro) AS cadastro,
        MAX(pacotecadastro.alteracao) AS alteracao
    FROM pacote
    INNER JOIN pacotecadastro ON pacote.idpacote = pacotecadastro.idpacote
    WHERE pacote.pacote LIKE '%$busca%'
    GROUP BY pacote.pacote";
} else {
    $query = "SELECT
        pacote.pacote,
        MAX(pacote.idpacote) AS idpacote,
        MAX(pacote.qtdPessoas) AS qtdPessoas,
        MAX(pacotecadastro.valorPacote) AS valorPacote,
        MAX(pacotecadastro.detalhes) AS detalhes,
        MAX(pacote.ativo) AS AtivoPacoteCadastro,
        MAX(pacotecadastro.cadastro) AS cadastro,
        MAX(pacotecadastro.alteracao) AS alteracao
    FROM pacote
    INNER JOIN pacotecadastro ON pacote.idpacote = pacotecadastro.idpacote
    GROUP BY pacote.pacote";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if ($rowCount > 0) {
    foreach ($result as $row) {
        $idPacote = $row["idpacote"];
        $ativoPacote = $row["AtivoPacoteCadastro"];

        echo '
            <tr>
                <td>' . $idPacote . '</td>
                <td>' . $row["pacote"] . '</td>
                <td>' . $row["detalhes"] . '</td>
                <td>' . $row["qtdPessoas"] . '</td>
                <td>' . $row["valorPacote"] . '</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">';

        if ($ativoPacote == 'A') {
            echo '
                <button type="button" class="btn btn-outline-success" onclick="ativarGeral(' . $idPacote . ', \'desativar\', \'ativarPacotes\', \'listarPacotes\', \'Pacote Desativado\');">
                    <i class="fa-solid fa-lock" title="Pacote Ativado"></i> Ativado
                </button>';
        } else {
            echo '
                <button type="button" class="btn btn-outline-secondary" onclick="ativarGeral(' . $idPacote . ',\'ativar\',\'ativarPacotes\',\'listarPacotes\', \'Pacote Ativado\');">
                    <i class="fa-solid fa-unlock" title="Pacote Desativado"></i> Desativado
                </button>';
        }

        echo '
                <a href="#" onclick="mostrarAlertaIdGet(' . $idPacote . ')">
                    <button type="button" class="btn btn-outline-info">
                        <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                    </button>
                </a>
        
                <button type="button" class="btn btn-outline-danger" onclick="excGeral(' . $idPacote . ', \'excluirPacotes\', \'listarPacotes\', \'Certeza que deseja excluir este pacote?\', \'Operação Irreversível!\')">
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
