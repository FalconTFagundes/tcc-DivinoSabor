<?php
include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Certifique-se de que há uma conexão estabelecida aqui
    $conexao = conectar();

    if ($conexao) {
        $idpacote = $_POST['idpacote'];
        $detalhesPacote = $_POST['detalhesPacote'];

        $idProdutos = $_POST['idproduto'];
        $quantidades = $_POST['quantidade'];

        try {
            $conexao->beginTransaction();

            // Cálculo do valor total do pacote
            $valorPacote = 0;

            // Insere os produtos agrupados na tabela pacotecadastro
            foreach ($idProdutos as $index => $idproduto) {
                $quantidade = $quantidades[$index];

                $sqlCadastrarProdutoNoPacote = $conexao->prepare("INSERT INTO pacotecadastro (idpacote, idproduto, cadastro, ativo, detalhes) VALUES (?, ?, NOW(), 'A', ?)");
                $sqlCadastrarProdutoNoPacote->bindValue(1, $idpacote, PDO::PARAM_INT);
                $sqlCadastrarProdutoNoPacote->bindValue(2, $idproduto, PDO::PARAM_INT);
                $sqlCadastrarProdutoNoPacote->bindValue(3, $detalhesPacote, PDO::PARAM_STR);
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
            $sqlAtualizarValorPacote->bindValue(':idpacote', $idpacote, PDO::PARAM_INT);
            $sqlAtualizarValorPacote->execute();

            // Commit da transação
            $conexao->commit();
            echo "Produtos cadastrados e valor do pacote atualizado com sucesso!";
        } catch (PDOException $e) {
            echo 'Exception -> ' . $e->getMessage();
            $conexao->rollback();
            echo 'Erro ao cadastrar produtos ou atualizar valor do pacote.';
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
}
?>
