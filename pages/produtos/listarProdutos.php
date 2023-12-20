<?php
include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

?>


<div style="text-align: center;" class="headerCalendar">
  <h1>Estoque Produtos</h1>

</div>


<!-- btn que chama a modal -->
<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadProdutos">
  <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar Produto
</button>


<button type="button" class="btn btn-outline-secondary" onclick="mostrarAlerta();">
  <i class="fa-solid fa-print" title="Gerar Relatório"></i>
  Gerar Relatório Geral
</button>


<br><br>
<i class="fa-solid fa-magnifying-glass fa-lg"></i>
<label for="inputSearch" class="labelSearch">
    <h5>Pesquisar Produto </h5>
</label>
<div class="input-group input-group-sm mb-3">
    <input type="text" id="buscarProduto" class="form-control inputSearch" aria-label="Small" placeholder="Pesquise">
</div>


<div style="height: 400px;">
  <table class="table-financeira table table-hover" id="tabelaProdutos">
    <thead>
      <tr>
        <th scope="col" width="10"><i class="fa-solid fa-hashtag"></i> Código</th>
        <th scope="col" width="20%"><i class="fa-solid fa-image"></i> Imagem</th>
        <th scope="col" width="20%"><i class="fa-solid fa-file-signature"></i> Nome</th>
        <th scope="col" width="10%"><i class="fa-solid fa-money-check-dollar"></i> Valor</th>
        <th scope="col" width="25%"><i class="fa-solid fa-clock"></i> Data e Hora de Cadastro</th>
        <th scope="col" width="15%"><i class="fa-solid fa-pen-to-square"></i> Ação</th>

      </tr>
    </thead>
    <tbody>

      <?php
      $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!

      $retornoListarProdutos = listarGeral('idprodutos, img, produto, valor, cadastro, alteracao, ativo', 'produtos');
      if (is_array($retornoListarProdutos) && !empty($retornoListarProdutos)) {
        foreach ($retornoListarProdutos as $itemProduto) {
          $idProduto = $itemProduto->idprodutos;
          $imgProduto = $itemProduto->img;
          $nomeProduto = $itemProduto->produto;
          $valorProduto = $itemProduto->valor;
          $cadastroProduto = $itemProduto->cadastro;
          $dataCadastroFormatada = formatarDataHoraBr($cadastroProduto); //passando para br pois será exibida
          $ativoProduto = $itemProduto->ativo;

      ?>

          <tr>
            <td scope="row"><?php echo $idProduto; ?></td>
            <td><img src="./assets/images/produtos/<?php echo $imgProduto; ?>" alt="Imagem Produto" class="img-thumbnail"></td>
            <td><?php echo $nomeProduto; ?></td>
            <td><?php echo $valorProduto; ?></td>
            <td><?php echo $dataCadastroFormatada; ?></td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <?php
                if ($ativoProduto == 'A') {
                ?>
                  <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idProduto; ?>,'desativar','ativarProdutos','listarProdutos', 'Produto Desativado com Sucesso');"> <i class="fa-solid fa-unlock" title="Produto Ativado"></i> Ativado</button>
                <?php
                } else {
                ?>
                  <button type='button' class='btn btn-outline-warning' onclick="ativarGeral(<?php echo $idProduto; ?>, 'ativar', 'ativarProdutos','listarProdutos', 'Produto Ativado com Sucesso');"><i class="fa-solid fa-lock" title="Produto Não Ativado"></i> Desativado</button>

                <?php
                }
                ?>
                <!-- passando id diretamente na URL - sem SEM AJAX -->
                <a href="#" onclick="mostrarAlertaIdGet('<?php echo $idProduto; ?>')">
                  <button type="button" class="btn btn-outline-info">
                    <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                  </button>
                </a>

                <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idProduto; ?>', 'excluirProdutos', 'listarProdutos', 'Certeza que deseja excluir este Produto?', 'Operação Irreversível!')"><i class="fa-solid fa-trash" title="Excluir"></i> Excluir</button>
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

<!-- Modal Cad Produto -->
<div class="modal fade" id="modalCadProdutos" tabindex="-1" role="dialog" aria-labelledby="modalCadPedido" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:blueviolet; color: white; ">
        <h5 class="modal-title" id="modalCadPedido">Cadastrar Produto <i class="fa-solid fa-user-plus" title="Cadastro de Produtos"></i></h5>
      </div>
      <form name="frmCadProdutos" method="POST" id="frmCadProdutos" class="frmCadProdutos" action="#">
        <div class="modal-body modaisCorpos">
          <div class="form-group">
            <label for="nomeProduto" class="form-label">Nome do produto</label>
            <input type="text" class="form-control inputModal" name="nomeProduto" id="nomeProduto" aria-describedby="nomeCliente" required>
          </div>
          <div class="form-group">
            <label for="imgProduto" class="form-label">Imagem Produto</label>
            <input type="file" class="form-control inputModal" name="imgProduto" id="imgProduto">
          </div>
          <div id="previewUploadProduto"></div>


          <div class="mb-3">
            <label for="valorProduto" class="form-label">Valor</label>
            <input type="text" class="form-control inputModal" name="valorProduto" id="valorProduto" required>
          </div>
        </div>
        <div class="modal-footer modaisCorpos">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
          <button type="submit" class="btn btn-primary" id="btnCadProdutos" onclick="cadProdutosUpload('frmCadProdutos');"><i class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function mostrarAlerta() {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório geral dos produtos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sim, gerar relatório!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Gerando Relatório :D',
          showConfirmButton: false,
          timer: 700
        });
        window.location.href = './gerarRelatorios/gerarRelatProdutos.php';
      }
    });
  }

  function mostrarAlertaIdGet(id) {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório do produto?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sim, gerar relatório!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Gerando Relatório :D',
          showConfirmButton: false,
          timer: 700
        });
        window.location.href = './gerarRelatorios/gerarRelatUnProduto.php?id=' + id;
      }
    });
  }

  /* PARTE DA FUNCTION DE UPLOADE */
  var redimensionarImgProduto = $('#previewUploadProduto').croppie({
    enableExif: true,
    enableOrientation: true,
    viewport: {
      width: 200,
      height: 200,
      type: 'square'
    },
    boundary: {
      width: 300,
      height: 300
    }
  });

  // Manipulador de mudança para o input de arquivo
  $('#imgProduto').on('change', function() {
    var lerProduto = new FileReader();
    lerProduto.onload = function(e) {
      redimensionarImgProduto.croppie('bind', {
        url: e.target.result
      });
    }

    lerProduto.readAsDataURL(this.files[0]);
  });

  function buscarNomeProduto(nome) {
    $.ajax({
      url: "pesquisarProduto.php",
      method: "POST",
      data: {
        nome: nome
      },
      success: function(data) {
        $('#tabelaProdutos tbody').html(data);
      }
    });
  }

  $(document).ready(function() {

    buscarNomeProduto();

    $('#buscarProduto').keyup(function() {
      var nome = $(this).val();
      if (nome != '') {
        buscarNomeProduto(nome);
      } else {
        buscarNomeProduto();
      }
    });
  });
</script>