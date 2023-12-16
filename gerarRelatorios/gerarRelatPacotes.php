<?php
// Carregar o Composer
require '../vendor/autoload.php';

// Incluir conexão com BD
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

        // QUERY para recuperar os registros do banco de dados
        $query_pacotes = "SELECT
            pacote.idpacote,
            pacote.pacote,
            pacote.qtdPessoas,
            pacotecadastro.valorPacote,
            pacotecadastro.detalhes,
            pacotecadastro.ativo AS AtivoPacoteCadastro,
            pacotecadastro.cadastro,
            pacotecadastro.alteracao,
            produto.idproduto,
            produto.produto AS NomeProduto
        FROM pacote
        INNER JOIN pacotecadastro ON pacote.idpacote = pacotecadastro.idpacote
        INNER JOIN produto ON pacotecadastro.idproduto = produto.idproduto;";

        // Prepara a QUERY
        $stmt = $conn->prepare($query_pacotes);

        // Verificar se a preparação da query foi bem-sucedida
        if (!$stmt) {
            throw new Exception("Falha ao preparar a consulta.");
        }

        // Executar a QUERY
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
            background-color: #f2f2f2; 
        }

        td {
            background-color: #fff; 
        }
    
        h2 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    
      
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
    
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 14px;
        }

        span{
            font-weight: bold;
        }
    
        td {
            background-color: #ffffff;
            font-size: 13px;
        }
    
        h4 {
            color: #555;
            font-size: 16px;
            margin-top: 10px;
        }
    
    </style>";

        $dados .= "<title>Relatório de Pacotes</title>";
        $dados .= "</head>";
        $dados .= "<body>";
        $dados .= "<h1>RELATÓRIO DE PACOTES</h1>";
        $dados .= "<h4>Usuário Emissor: <b>" . $_SESSION['nomeUser'] . "</b></h4>";

        // Verificar se existem registros para processar
        if ($stmt->rowCount() > 0) {
            $pacote_info = null; // Inicializa a variável

            while ($row_pedidos = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row_pedidos);

                // Exibe as informações do pacote apenas uma vez
                if ($pacote_info != $idpacote) {
                    // Fechar a tabela anterior, se já existir uma
                    if ($pacote_info !== null) {
                        $dados .= "</table>"; // Fecha a tabela
                        $dados .= "<hr>"; // Adiciona uma linha horizontal após cada pacote
                    }

                    $dados .= "<h2>$pacote</h2>";
                    $dados .= "<table>";
                    $dados .= "<tr><th>Capacidade:</th><td>$qtdPessoas pessoa(s)</td></tr>";
                    $dados .= "<tr><th>Valor Total do Pacote:</th><td>$valorPacote</td></tr>";
                    $dados .= "<tr><th>Detalhes do Pacote:</th><td>$detalhes</td></tr>";
                    $dados .= "<tr><th>Status do Pacote:</th><td>";
                    $dados .= ($AtivoPacoteCadastro == 'A') ? "<span style='color: #32CD32;'>Ativo</span>" : "<span style='color: #d9534f;'>Inativo</span>";
                    $dados .= "</td></tr>";
                    $dados .= "<tr><th>Data e Hora de Cadastro:</th><td>" . formatarDataHoraBr($cadastro) . "</td></tr>";
                    $dados .= "<tr><th>Última Alteração:</th><td>" . formatarDataHoraBr($alteracao) . "</td></tr>";
                }

                // Adiciona informações do produto à tabela
                $dados .= "<tr><th>ID do Produto: $idproduto</th><td><span>Nome do Produto:</span> $NomeProduto</td></tr>";

                $pacote_info = $idpacote;
            }

            $dados .= "</table>"; // Fecha a última tabela
            $dados .= "<hr>"; // Adiciona uma linha horizontal após o último
        } else {
            $dados .= "<p>Nenhum registro encontrado.</p>";
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
