<?php
// Carregar o Composer
require '../vendor/autoload.php';

// Incluir conexao com BD
include_once '../config/constantes.php';
include_once '../config/conexao.php';
include_once '../func/dashboard.php';

// Referenciar o namespace Dompdf
use Dompdf\Dompdf;

try {
    // Chama a função conectar e atribui o resultado à variável $conn
    $conn = conectar();

    // Se a conexão for bem-sucedida, continue com o restante do código
    if ($conn) {
        // Recebendo ID diretamente pela URL - sem Ajax e verificando ser um número inteiro
        $idIngredUn = isset($_GET['id']) ? intval($_GET['id']) : null;

        // QUERY para recuperar os registros do banco de dados
        $query_IngredUn = "SELECT idingredientes, nomeIngred, quantIngred, pesoUnit, dataComp, precoUnit, precoTot, dataValidad, cadastro, alteracao, ativo FROM ingredientes WHERE idingredientes = :idIngredUn";

        // Prepara a QUERY
        $stmt = $conn->prepare($query_IngredUn);

        // Verificar se a preparação da query foi bem-sucedida
        if (!$stmt) {
            throw new Exception("Falha ao preparar a consulta.");
        }

        // Preparar e executar a QUERY
        $stmt->bindParam(':idIngredUn', $idIngredUn, PDO::PARAM_INT);
        $stmt->execute();

        // Informacoes para o PDF
        $dados = "<!DOCTYPE html>";
        $dados .= "<html lang='pt-br'>";
        $dados .= "<head>";
        $dados .= "<meta charset='UTF-8'>";
        $dados .= "<style>
            body {
                font-family: 'Arial', sans-serif;
                color: #333;
                margin: 20px;
            }

            h1 {
                color: #9E77F1;
                text-align: center;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }

            h4 {
                color: #333;
                margin-top: 10px;
            }

            b {
                color: #333;
            }

            hr {
                border-top: 1px solid;
                margin-top: 20px;
                margin-bottom: 30px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2; /* Cor de fundo mais clara para cabeçalho da tabela */
            }

            td {
                background-color: #fff; /* Cor de fundo branca para células de dados */
            }
        </style>";
        $dados .= "<title>Relatório de Ingredientes</title>";
        $dados .= "</head>";
        $dados .= "<body>";
        $dados .= "<h1>RELATÓRIO DE INGREDIENTES</h1>";

        // Adicionar o bloco if para verificar se a sessão está definida antes de acessar
        $dados .= "<h4>Usuário Emissor: <b>" . (isset($_SESSION['nomeUser']) ? $_SESSION['nomeUser'] : 'Usuário Desconhecido') . "</b></h4>";

        // Ler os registros retornados do BD
        while ($row_ingredUn = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row_ingredUn);

            $dados .= "<table>";
            $dados .= "<tr><th>Código do Ingrediente</th><td>$idingredientes</td></tr>";
            $dados .= "<tr><th>Nome</th><td>$nomeIngred</td></tr>";
            if ($ativo == 'A') {
                $status = "<span style='color: #32CD32;'>Ativado</span>";
            } else {
                $status = "<span style='color: #d9534f;'>Desativado</span>";
            }

            $dados .= "<tr><th>Status do Ingrediente</th><td>$status</td></tr>";
            $dados .= "<tr><th>Quantidade adquirida</th><td>$quantIngred</td></tr>";
            $dados .= "<tr><th>Peso unitário</th><td>$pesoUnit</td></tr>";
            $dados .= "<tr><th>Valor unitário</th><td>$precoUnit</td></tr>";
            $dados .= "<tr><th>Valor total</th><td>$precoTot</td></tr>";
            $dados .= "<tr><th>Data da compra</th><td>$dataComp</td></tr>";
            $dados .= "<tr><th>Data de validade</th><td>$dataValidad</td></tr>";
            $dataCadastroFormatada = formatarDataHoraBr($cadastro);
            $dados .= "<tr><th>Data e Hora de Cadastro</th><td>$dataCadastroFormatada</td></tr>";
            $dataAlteracaoFormatada = formatarDataHoraBr($alteracao);
            $dados .= "<tr><th>Última Alteração</th><td>$dataAlteracaoFormatada</td></tr>";
            $dados .= "</table>";

            // Adiciona uma linha horizontal após cada registro
            $dados .= "<hr>";
        }

        $dados .= "</body>";

        // Instanciar e usar a classe dompdf
        $dompdf = new Dompdf(['enable_remote' => true]);

        // Instanciar o método loadHtml e enviar o conteúdo do PDF
        $dompdf->loadHtml($dados);

        // Configurar o tamanho e a orientação do papel
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar o HTML como PDF
        $dompdf->render();

        // Gerar o PDF
        $dompdf->stream();
    } else {
        echo "Erro: Falha na conexão com o banco de dados.";
    }
} catch (PDOException $e) {
    echo "Erro no PDO: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>



