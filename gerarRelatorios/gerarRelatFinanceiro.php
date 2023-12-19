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
        // Informacoes para o PDF
        $dados = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <style>
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
                .ativo {
                    color: #32CD32; /* Verde */
                }
                .inativo {
                    color: #d9534f; /* Vermelho */
                }
            </style>
            <title>Relatório Financeiro</title>
        </head>
        <body>
            <h1>RELATÓRIO - PAINEL FINANCEIRO</h1>
    ";

        // Dados Financeiros
        $dadosFinanceiros = $_SESSION['dados_painel_financeiro'] ?? null;
        if ($dadosFinanceiros) {
            $dados .= "
            <h2>Dados Financeiros</h2>
            <table>
                <tr><th>Quantidade de Clientes</th><td>{$dadosFinanceiros['qtdClientes']}</td></tr>
                <tr><th>Vendas Mensais</th><td>{$dadosFinanceiros['vendasMensais']}</td></tr>
                <tr><th>Déficit</th><td>{$dadosFinanceiros['deficit']}</td></tr>
                <tr><th>Lucro</th><td>{$dadosFinanceiros['lucro']}</td></tr>
            </table>
            <hr>
        ";
        }

        // Vendas recentes
        $dados .= "
        <h2>Vendas recentes</h2>
        <table>
            <thead>
                <tr>
                    <th>Código do pedido</th>
                    <th>Nome do cliente</th>
                    <th>Pacote escolhido</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    ";

        $retornoUltimasVendas = listarGeralPacoteInnerjoinFinanceiro();
        foreach ($retornoUltimasVendas as $itemUltimasVendas) {
            $idPedidoUltimasVendas = $itemUltimasVendas->idpacote;
            $nomeClienteUltimasVendas = $itemUltimasVendas->nome;
            $pacoteEscolhidoultimasVendas = $itemUltimasVendas->pacote;
            $statusUltimasVendas = $itemUltimasVendas->ativo;

            $statusTexto = $statusUltimasVendas == 'A' ? 'Ativado' : 'Inativo';
            $statusClasse = $statusUltimasVendas == 'A' ? 'ativo' : 'inativo';

            $dados .= "
            <tr>
                <td>{$idPedidoUltimasVendas}</td>
                <td>{$nomeClienteUltimasVendas}</td>
                <td>{$pacoteEscolhidoultimasVendas}</td>
                <td class='{$statusClasse}'>{$statusTexto}</td>
            </tr>
        ";
        }

        $dados .= "
            </tbody>
        </table>
        </div>
    ";

        // Clientes recentes
        $dados .= "
        <h2>Clientes recentes</h2>
        <table>
            <thead>
                <tr>
                    <th>Código do cliente</th>
                    <th>Nome do cliente</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    ";

        $ultimosClientes = obterUltimosClientes();
        foreach ($ultimosClientes as $cliente) {
            $statusTexto = $cliente['ativo'] == 'A' ? 'Ativo' : 'Inativo';
            $statusClasse = $cliente['ativo'] == 'A' ? 'ativo' : 'inativo';

            $dados .= "
            <tr>
                <td>{$cliente['idclientes']}</td>
                <td>{$cliente['nome']}</td>
                <td class='{$statusClasse}'>{$statusTexto}</td>
            </tr>
        ";
        }

        $dados .= "
            </tbody>
        </table>
        </div>
        </body>
        </html>
    ";
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
