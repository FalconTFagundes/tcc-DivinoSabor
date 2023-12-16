<?php
include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

$resultadoCadastro = '';

$opcoesPacote = obterOpcoesDoBanco('pacote', 'idpacote', 'pacote');

$opcoesProduto = obterOpcoesDoBanco('produto', 'idproduto', 'produto');

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Título Aqui</title>
    <!-- Adicione os links para seus arquivos de estilo e scripts aqui -->
</head>

<body>
    <h1 style="text-align: center;">Pacotes</h1>

    <!-- btn que chama a modal -->
    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadPacotes">
        <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar Pacote
    </button>

    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#cadPacoteProdutoModal">
        <i class="fa-solid fa-plus" title="Cadastrar"></i> Itens do Pacote
    </button>

    <button type="button" class="btn btn-outline-secondary" onclick="mostrarAlerta();">
        <i class="fa-solid fa-print" title="Gerar Relatório"></i>
        Gerar Relatório Geral
    </button>

    <br><br>

    <div style="height: 400px;">
        <table class="table-financeira table table-hover">
            <thead>
                <tr>
                    <th scope="col" width="5">Código</th>
                    <th scope="col" width="15%">Nome</th>
                    <th scope="col" width="20%">Detalhes</th>
                    <th scope="col" width="20%">Quantitativo que Alcança</th>
                    <th scope="col" width="20%">Valor</th>
                    <th scope="col" width="25%">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!

                $retornoListarPacotes = obterPacotes();
                if (is_array($retornoListarPacotes) && !empty($retornoListarPacotes)) {
                    foreach ($retornoListarPacotes as $itemPacote) {
                        $idPacote = $itemPacote['idpacote'];
                        $nomePacote = $itemPacote['pacote'];
                        $detalhesPacote = $itemPacote['detalhes'];
                        $qtdPeoplePacote = $itemPacote['qtdPessoas']; 
                        $valorPacote = $itemPacote['valorPacote']; 
                        $cadastroPacote = $itemPacote['cadastro'];
                        $ativoPacote = $itemPacote['ativo'];
                        ?>
                        <tr>
                            <td scope="row"><?php echo $idPacote; ?></td>
                            <td><?php echo $nomePacote; ?></td>
                            <td><?php echo $detalhesPacote; ?></td>
                            <td><?php echo $qtdPeoplePacote; ?></td>
                            <td><?php echo $valorPacote; ?></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <?php
                                    if ($ativoPacote == 'A') {
                                    ?>
                                        <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idPacote; ?>,'desativar','ativarPacotes','listarPacotes', 'Pacote Desativado com Sucesso');"> <i class="fa-solid fa-unlock" title="Pacote Ativado"></i> Ativado</button>
                                    <?php
                                    } else {
                                    ?>
                                        <button type='button' class='btn btn-outline-warning' onclick="ativarGeral(<?php echo $idPacote; ?>, 'ativar', 'ativarPacotes','listarPacotes', 'Pacote Ativado com Sucesso');"><i class="fa-solid fa-lock" title="Pacote Não Ativado"></i> Desativado</button>
                                    <?php
                                    }
                                    ?>
                                    <!-- passando id diretamente na URL - sem SEM AJAX -->
                                    <a href="#" onclick="mostrarAlertaIdGet('<?php echo $idPacote; ?>')">
                                        <button type="button" class="btn btn-outline-info">
                                            <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                                        </button>
                                    </a>

                                    <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idPacote; ?>', 'excluirPacotes', 'listarPacotes', 'Certeza que deseja excluir este pacote?', 'Operação Irreversível!')"><i class="fa-solid fa-trash" title="Excluir"></i> Excluir</button>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<div class='alert alert-warning' style='text-align: center;' role='alert'>";
                    echo "Nenhum Registro Encontrado";
                    echo "</div>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Cadastrar Pacote -->
    <div class="modal fade" id="modalCadPacotes" tabindex="-1" role="dialog" aria-labelledby="modalCadPacote" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:blueviolet; color: white; ">
                    <h5 class="modal-title" id="modalCadPacotes">Cadastrar Pacote <i class="fa-solid fa-user-plus" title="Cadastro de Pacotes"></i></h5>
                </div>
                <form name="frmCadPacotes" method="POST" id="frmCadPacotes" class="frmCadPacotes" action="#">
                    <div class="modal-body modaisCorpos">
                        <div class="form-group">
                            <label for="nomePacote" class="form-label">Nome do Pacote</label>
                            <input type="text" class="form-control inputModal" name="nomePacote" id="nomePacote" aria-describedby="nomePacote" required>
                        </div>
                        <div class="form-group">
                            <label for="quantitativoPacote" class="form-label">Quantitativo que Alcança</label>
                            <input type="number" class="form-control inputModal" name="quantitativoPacote" id="quantitativoPacote" required>
                        </div>
                    </div>
                    <div class="modal-footer modaisCorpos">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
                        <button type="submit" class="btn btn-primary" id="btnCadPacotes" onclick="cadGeral('frmCadPacotes','modalCadPacotes','cadPacote','listarPacotes');"><i class="fa-solid fa-check" title="Cadastrar Pacote"></i> Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar itens no pacote -->
    <div class="modal fade" id="cadPacoteProdutoModal" tabindex="-1" role="dialog" aria-labelledby="pacoteProdutoModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:blueviolet; color: white; ">
                    <h5 class="modal-title" id="modalCadPacotes">Cadastrar Itens no Pacote <i class="fa-regular fa-pen-to-square" title="Cadastro de Pacotoes"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modaisCorpos">
                    <form action="#" name="frmCadItemPacote" id="frmCadItemPacote" method="post">
                        <div class="form-group">
                            <label for="idpacote">Escolha o pacote</label>
                            <select class="form-control inputModal" name="idpacote" id="idpacote">
                                <?php foreach ($opcoesPacote as $opcao) : ?>
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
                                    <select class="form-control inputModal" name="idproduto[]" id="idproduto">
                                        <?php foreach ($opcoesProduto as $opcao) : ?>
                                            <option value="<?php echo $opcao['idproduto']; ?>">
                                                <?php echo $opcao['produto']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quantidade">Quantidade</label>
                                    <input type="number" class="form-control inputModal" name="quantidade[]" placeholder="Quantidade">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="adicionarProduto()">Adicionar Produto</button>
                        <br><br>
                        <div class="form-group">
                            <label for="detalhesPacote" class="form-label">Detalhes Pacote</label>
                            <input type="text" class="form-control inputModal" name="detalhesPacote" id="detalhesPacote" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
                            <button type="submit" class="btn btn-primary" onclick="cadGeral('frmCadItemPacote','cadPacoteProdutoModal','cadPacoteVenda', 'listarPacotes');"><i class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
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
                                        <select class="form-control inputModal" name="idproduto[]" id="idproduto">
                                            <?php foreach ($opcoesProduto as $opcao) : ?>
                                                <option value="<?php echo $opcao['idproduto']; ?>"><?php echo $opcao['produto']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantidade">Quantidade</label>
                                        <input type="number" class="form-control inputModal" name="quantidade[]" placeholder="Quantidade">
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
</body>

</html>
