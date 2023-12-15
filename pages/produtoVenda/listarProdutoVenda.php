<?php
include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';


$resultadoCadastro = '';

$opcoesPacote = obterOpcoesDoBanco('pacote', 'idpacote', 'pacote');

$opcoesProduto = obterOpcoesDoBanco('produto', 'idproduto', 'produto');

?>
<h1 style="text-align: center;">Pacotes</h1>
<!-- Modal Cadastrar Pacote -->
<div class="modal fade" id="pacoteProdutoModal" tabindex="-1" role="dialog" aria-labelledby="pacoteProdutoModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:blueviolet; color: white; ">
                <h5 class="modal-title" id="modalCadPedido">Cadastrar Pacote <i class="fa-regular fa-pen-to-square"
                        title="Cadastro de Pedidos"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./pages/produtoVenda/cadProdutoVenda.php" method="post">
                    <div class="form-group">
                        <label for="idpacote">Escolha o pacote</label>
                        <select class="form-control" name="idpacote" id="idpacote">
                            <?php foreach ($opcoesPacote as $opcao): ?>
                                <option value="<?php echo $opcao['idpacote']; ?>">
                                    <?php echo $opcao['pacote']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="produtos-container" class="mb-3">
                        <div class="produto">
                            <div class="form-group">
                                <label for="idproduto">Escolha os produtos</label>
                                <select class="form-control" name="idproduto[]" id="idproduto">
                                    <?php foreach ($opcoesProduto as $opcao): ?>
                                        <option value="<?php echo $opcao['idproduto']; ?>">
                                            <?php echo $opcao['produto']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" class="form-control" name="quantidade[]" placeholder="Quantidade">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-secondary w-100" onclick="adicionarProduto()">Adicionar
                        Produto</button>
                    <br>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark"
                                title="Fechar Modal"></i> Fechar</button>
                        <button type="submit" class="btn btn-primary"><i
                                class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
                    </div>
                </form>

                <script>
                    function adicionarProduto() {
                        var container = document.getElementById('produtos-container');
                        var produtoDiv = document.createElement('div');

                        produtoDiv.innerHTML = `
                            <br>
                            <div class="produto">
                                <div class="form-group">
                                    <label for="idproduto">Escolha os produtos</label>
                                    <select class="form-control" name="idproduto[]" id="idproduto">
                                        <?php foreach ($opcoesProduto as $opcao): ?>
                                                <option value="<?php echo $opcao['idproduto']; ?>"><?php echo $opcao['produto']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="quantidade">Quantidade</label>
                                    <input type="number" class="form-control" name="quantidade[]" placeholder="Quantidade">
                                </div>
                            </div>
                            <br> <br>`;

                        container.appendChild(produtoDiv);
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Modal Criar Pacote -->
<div class="modal fade" id="pacoteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar ingredientes</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./pages/produtoVenda/cadPacote.php" method="post">
                    <div class="mb-3">
                        <label for="nomePacote" class="form-label">Pacote</label>
                        <input type="text" class="form-control" name="nomePacote" id="nomePacote">
                    </div>

                    <div class="mb-3">
                        <label for="imgPacote" class="form-label">Imagem</label>
                        <input type="text" class="form-control" name="imgPacote" id="imgPacote">
                    </div>

                    <div class="mb-3">
                        <label for="quantPessoas" class="form-label">Quantidade de pessoas</label>
                        <input type="number" class="form-control" name="quantPessoas" id="quantPessoas">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php



try {
    $conexao = conectar();

    if ($conexao) {
        $sqlListarPacotesDetalhados = "SELECT 
                                            pc.idpacote AS 'Número do Pacote', 
                                            p.pacote AS 'Nome do Pacote', 
                                            GROUP_CONCAT(pr.produto, ' - Quantidade: ', pc.quantidade SEPARATOR ', ') AS 'Descrição',
                                            pc.quantidade AS 'Quantidade de Pessoas', 
                                            pc.valorPacote AS 'Valor do Pacote'
                                        FROM pacotecadastro pc 
                                        INNER JOIN pacote p ON pc.idpacote = p.idpacote 
                                        INNER JOIN produto pr ON pc.idproduto = pr.idproduto 
                                        GROUP BY pc.idpacote";

        $stmt = $conexao->prepare($sqlListarPacotesDetalhados);
        $stmt->execute();
        $pacotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
       
                
                <?php
                if ($pacotes) {
                    echo '<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target=" #pacoteModal">
                     Nome do Pacote
                  </button>
                 
                  
                  <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#pacoteProdutoModal">
                   Itens do Pacote
                </button>
                  <br><br>';
                        
                  echo '<div style="height: 400px;">
                  <table class="table-financeira table table-hover mx-auto">';
          echo '<thead><tr>';
          foreach ($pacotes[0] as $coluna => $valor) {
              echo '<th class="text-center" style="padding-right: 170px;">' . $coluna . '</th>';
          }
          echo '<th class="text-center">Ação</th>'; // Adiciona a coluna Ação
          echo '</tr></thead><tbody>';
          echo '</tr>';
          
          foreach ($pacotes as $pacote) {
              echo '<tr>';
              foreach ($pacote as $valor) {
                  // Adicione a classe text-center para centralizar horizontalmente
                  echo '<td class="text-center">' . $valor . '</td>';
              }
          
              echo '<td class="text-center">';
              echo '<button type="button" class="btn btn-outline-primary mx-3" <i class="fa-solid fa-unlock" title="Pedido Não Concluído"></i></i> Ativar</button>';
              echo '<button type="button" class="btn btn-outline-danger mx-3" <i class="fa-solid fa-unlock" title="Pedido Não Concluído"></i></i> Excluir</button>';
              echo '</td>';
          
              echo '</tr>';
          }
          
          echo '</tbody></table></div>';
          
                } else {
                    echo 'Nenhum pacote encontrado.';
                }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>
  