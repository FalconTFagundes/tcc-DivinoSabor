<?php
include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

$resultadoCadastro = '';

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Certifique-se de que há uma conexão estabelecida aqui
    $conexao = conectar(); // Supondo que você tenha uma função conectar() para estabelecer a conexão com o banco de dados

    if ($conexao) {
        $idpacote = $_POST['idpacote'];
        $detalhesPacotes = $_POST['detalhesPacote'];

        // Verifica se o pacote já existe na tabela
        $sqlVerificarPacote = $conexao->prepare("SELECT COUNT(*) as total FROM pacotecadastro WHERE idpacote = ?");
        $sqlVerificarPacote->bindValue(1, $idpacote, PDO::PARAM_INT);
        $sqlVerificarPacote->execute();
        $totalPacotes = $sqlVerificarPacote->fetch(PDO::FETCH_ASSOC)['total'];

        if ($totalPacotes > 0) {
            echo "Este pacote já foi cadastrado anteriormente.";
        } else {
            $idProdutos = $_POST['idproduto'];
            $quantidades = $_POST['quantidade'];

            try {
                $conexao->beginTransaction();

                // Obtém o ID do novo pacote (pode ser gerado automaticamente pelo banco de dados, por exemplo, um campo autoincrement)
                $sqlObterNovoIdPacote = $conexao->prepare("SELECT MAX(idpacote) + 1 AS novo_id FROM pacote");
                $sqlObterNovoIdPacote->execute();
                $novoIdPacote = $sqlObterNovoIdPacote->fetch(PDO::FETCH_ASSOC)['novo_id'];

                // Insere um novo pacote
                $sqlInserirNovoPacote = $conexao->prepare("INSERT INTO pacote (idpacote, cadastro, ativo) VALUES (?, NOW(), 'A')");
                $sqlInserirNovoPacote->bindValue(1, $novoIdPacote, PDO::PARAM_INT);
                $sqlInserirNovoPacote->execute();

                // Cálculo do valor total do pacote
                $valorPacote = 0;

                // Insere os produtos agrupados na tabela pacotecadastro
                foreach ($idProdutos as $index => $idproduto) {
                    $quantidade = $quantidades[$index];

                    $sqlCadastrarProdutoNoPacote = $conexao->prepare("INSERT INTO pacotecadastro (idpacote, idproduto, quantidade, cadastro, ativo, detalhes) VALUES (?, ?, ?, NOW(), 'A', ?)");
                    $sqlCadastrarProdutoNoPacote->bindValue(1, $novoIdPacote, PDO::PARAM_INT);
                    $sqlCadastrarProdutoNoPacote->bindValue(2, $idproduto, PDO::PARAM_INT);
                    $sqlCadastrarProdutoNoPacote->bindValue(3, $quantidade, PDO::PARAM_INT);
                    $sqlCadastrarProdutoNoPacote->bindValue(4, $detalhesPacotes, PDO::PARAM_STR);
                    $sqlCadastrarProdutoNoPacote->execute();

                    // Cálculo do valor total do pacote
                    $sqlValorProduto = $conexao->prepare("SELECT valor FROM produto WHERE idproduto = ?");
                    $sqlValorProduto->bindValue(1, $idproduto, PDO::PARAM_INT);
                    $sqlValorProduto->execute();
                    $valorProduto = $sqlValorProduto->fetch(PDO::FETCH_ASSOC)['valor'];

                    // Convertendo as variáveis para tipos numéricos antes de multiplicar
                    $valorPacote += floatval($valorProduto) * intval($quantidade);
                }


                // Atualização do valor do pacote na tabela de pacotes
                // Atualização do valor do pacote na tabela de pacotecadastro
                $sqlAtualizarValorPacote = $conexao->prepare("UPDATE pacotecadastro SET valorPacote = :valor WHERE idpacote = :idpacote");
                $sqlAtualizarValorPacote->bindValue(':valor', $valorPacote, PDO::PARAM_STR);
                $sqlAtualizarValorPacote->bindValue(':idpacote', $novoIdPacote, PDO::PARAM_INT);
                $sqlAtualizarValorPacote->execute();


                // Commit da transação
                $conexao->commit();
                echo "Produtos cadastrados e valor do pacote atualizado com sucesso!";
            } catch (PDOException $e) {
                echo 'Exception -> ' . $e->getMessage();
                $conexao->rollback();
                echo 'Erro ao cadastrar produtos ou atualizar valor do pacote.';
            }
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
}
// ... (restante do seu código)
