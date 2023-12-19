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
        // QUERY para recuperar os registros do banco de dados
        $query_ingredientes = "SELECT 
        idingredientes, 
        nomeIngred, 
        img, 
        quantIngred, 
        pesoUnit, 
        pesoTotal, 
        precoUnit,
        precoTotal, 
        dataComp, 
        dataValidad, 
        codigo, 
        cadastro, 
        alteracao, 
        ativo
     FROM ingredientes";
        // Prepara a QUERY
        $stmt = $conn->prepare($query_ingredientes);

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
        $dados .= "<title>Relatório de Ingredientes</title>";
        $dados .= "<style>
    body {
        font-family: 'Arial', sans-serif;
        color: #333;
        margin: 20px;
    }

    h1 {
        color: #9E77F1;
        text-align: center;
        border-bottom: 1px solid #333;
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
        background-color: #f2f2f2;
    }

    td {
        background-color: #fff;
    }
</style>";
        $dados .= "</head>";
        $dados .= "<body>";
        $dados .= "<h1>RELATÓRIO DE INGREDIENTES</h1>";
        $dados .= "<h4>Usuário Emissor: <b>" . $_SESSION['nomeUser'] . "</b></h4>";

        // Ler os registros retornados do BD
        while ($row_ingredientes = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row_ingredientes);

            $dados .= "<table>";
            $dados .= "<tr><th>Código do Ingrediente</th><td>$idingredientes</td></tr>";
            $dados .= "<tr><th>Nome do ingrediente</th><td>$nomeIngred</td></tr>";
            if ($ativo == 'A') {
                $status = "<span style='color: #32CD32;'>Ingrediente em Uso</span>";
            } else {
                $status = "<span style='color: #d9534f;'>Ingrediente em Desuso</span>";
            }
            $dados .= "<tr><th>Status do Ingrediente</th><td>$status</td></tr>";
            $dados .= "<tr><th>Quantidade Armazenada</th><td>$quantIngred</td></tr>";
            $dataCompraFormatada = formatarDataBr($dataComp);
            $dados .= "<tr><th>Data da compra</th><td>$dataCompraFormatada</td></tr>";
            $dados .= "<tr><th>Peso unitário</th><td>" . number_format($pesoUnit, 2, ',', '.') . " Kg</td></tr>";
            $dados .= "<tr><th>Peso Total</th><td>" . number_format($pesoTotal, 2, ',', '.') . " Kg</td></tr>";

            $dados .= "<tr><th>Valor unitário</th><td>R$ " . number_format($precoUnit, 2, ',', '.') . "</td></tr>";
            $dados .= "<tr><th>Preço total</th><td>R$ " . number_format($precoTotal, 2, ',', '.') . "</td></tr>";

            $dataValidadeFormatada = formatarDataBr($dataValidad);
            $dados .= "<tr><th>Data de validade</th><td>$dataValidadeFormatada</td></tr>";
            $dataCadastroFormatada = formatarDataHoraBr($cadastro);
            $dados .= "<tr><th>Data e Hora de Cadastro</th><td>$dataCadastroFormatada</td></tr>";
            $dataAlteracaoFormatada = formatarDataHoraBr($alteracao);
            $dados .= "<tr><th>Última Alteração</th><td>$dataAlteracaoFormatada</td></tr>";
            $dados .= "</table>";


            // Adiciona uma linha horizontal após cada registro
            $dados .= "<hr>";
        }

        $dados .= "</body>";
        $dados .= "</html>";


        // Instanciar e usar a classe dompdf
        $dompdf = new Dompdf(['enable_remote' => true]);

        // Instanciar o metodo loadHtml e enviar o conteudo do PDF
        $dompdf->loadHtml($dados);

        // Configurar o tamanho e a orientacao do papel
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
