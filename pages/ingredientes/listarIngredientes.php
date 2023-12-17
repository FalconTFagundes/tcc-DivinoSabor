<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

?>

<h1 style="text-align: center;">Estoque Ingredientes</h1>



<!-- btn que chama a modal -->
<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadIngred">
  <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar ingrediente
</button>


<button type="button" class="btn btn-outline-secondary" onclick="mostrarAlerta();">
  <i class="fa-solid fa-print" title="Gerar Relatório"></i>
  Gerar Relatório Geral
</button>


  <button type="button" class="btn btn-outline-info linkMenu" idMenu="listarProduto">
    <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar Produto
  </button>


<br><br>



<div style="height: 400px;">
  <table class="table-financeira table table-hover">
    <thead>
      <tr>
        <th scope="col" width="5%">Código</th>
        <th scope="col" width="10%">Nome</th>
        <th scope="col" width="5%">Quantidade</th>
        <th scope="col" width="15%">Peso unitário</th>
        <th scope="col" width="10%">Valor unitário</th>
        <th scope="col" width="10%">Data da compra</th>
        <th scope="col" width="10%">Valor total</th>
        <th scope="col" width="10%">Data de validade</th>
        <th scope="col" width="25%" class="text-center">Ação</th>
      </tr>
    </thead>
    <tbody>



      <?php
      $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!
      $dataSeteDiasAntes = date("Y-m-d", strtotime("-7 days"));



      $listarIngred = listarGeral('idingredientes, nomeIngred, quantIngred, pesoUnit, precoUnit, dataComp, dataValidad, precoTot, ativo', 'ingredientes');
      foreach ($listarIngred as $itemIngred) {
        $idIngred = $itemIngred->idingredientes;
        $nomeIngred = $itemIngred->nomeIngred;
        $quantIngred = $itemIngred->quantIngred;
        $pesoUnit = $itemIngred->pesoUnit;
        $precoUnit = $itemIngred->precoUnit;
        $dataComp = $itemIngred->dataComp;
        $precoTot = $itemIngred->precoTot;
        $dataValidad = $itemIngred->dataValidad;
        $ativoIngred = $itemIngred->ativo;


        $dataValidadFormat = date("d/m/Y", strtotime($dataValidad)); //passando para o formato br


        $classeData = '';
        if (strtotime($dataAtual) >= strtotime($dataValidad)) {
          $classeData = 'entregaVermelha';
        } elseif (strtotime($dataAtual) >= strtotime('-7 days', strtotime($dataValidad)) && strtotime($dataAtual) < strtotime($dataValidad)) {
          $classeData = 'entregaAmarela';
        } else {
          $classeData = 'entregaVerde';
        }
      ?>

        <tr>
          <td scope="row"><?php echo $idIngred; ?></td>
          <td><?php echo $nomeIngred; ?></td>
          <td><?php echo $quantIngred; ?></td>
          <td><?php echo $pesoUnit; ?></td>
          <td><?php echo $precoUnit; ?></td>
          <td><?php echo $dataComp; ?></td>
          <td><?php echo $precoTot; ?></td>
          <td class="<?php echo $classeData; ?>"><?php echo  $dataValidadFormat; ?></td>

          <td>
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <?php
              if ($ativoIngred == 'A') {
              ?>
                <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idIngred; ?>,'desativar','ativarIngredientes','listarIngredientesEstoque', 'Uso suspenso');"> <i class="fa-solid fa-unlock" title="satus ingrediente"></i>Em uso</button>
              <?php
              } else {
              ?>
                <button type='button' class='btn btn-outline-secondary' onclick="ativarGeral(<?php echo $idIngred; ?>, 'ativar', 'ativarIngredientes','listarIngredientesEstoque', 'Ingrediente em uso');"><i class="fa-solid fa-lock" title="Pedido Concluído"></i>Uso suspenso</button>

              <?php
              }
              ?>
              <!-- passando id diretamente na URL - sem SEM AJAX -->
              <a href="#" onclick="mostrarAlertaIdGet('<?php $idIngred; ?>')">
                <button type="button" class="btn btn-outline-info btnGerarRelatPedidoUn">
                  <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                </button>
              </a>
              <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idIngred ?>', 'excluirIngredientes', 'listarIngredientesEstoque', 'Certeza que deseja excluir?', 'Operação Irreversível!')"><i class="fa-solid fa-trash" title="Excluir ingredientes"></i> Excluir </button>
            </div>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</div>

<!-- Modal Cad Pedido -->
<div class="modal fade" id="modalCadIngred" tabindex="-1" role="dialog" aria-labelledby="modalCadIngred" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:blueviolet; color: white; ">
        <h5 class="modal-title" id="modalCadPedido">Cadastrar Pedido <i class="fa-regular fa-pen-to-square" title="Cadastro de Pedidos"></i></h5>
      </div>
      <form name="frmCadIngred" method="POST" id="frmCadIngred" class="frmCadIngred" action="cadIngredienteRstoque.php">
        <div class="modal-body modaisCorpos">
          <div class="form-group">
            <label for="nomePedido" class="form-label">Selecione o Cliente</label>
            <!-- select com nome dos clientes cadastrados no banco de dados :D -->

            <div class="form-group">
              <label for="pedido" class="form-label">Ingrediente</label>
              <input type="text" class="form-control inputModal" name="nomeIngred" id="ingrediente" required>
            </div>

            <div class="form-group">
              <label for="pedido" class="form-label">Quantidade</label>
              <input type="text" class="form-control inputModal" name="quantIngred" id="quantidade" required>
            </div>

            <div class="form-group">
              <label for="pedido" class="form-label">Peso Unitário</label>
              <input type="text" class="form-control inputModal" name="pesoIngred" id="pesoIngrediente" required oninput="formatarNumeroDecimal(this)">
            </div>

            <div class="form-group">
              <label for="pedido" class="form-label">Valor Unitário</label>
              <input type="text" class="form-control inputModal" name="valIngred" id="valorngrediente" required oninput="formatarNumeroDecimal(this)">
            </div>

            <div class="mb-3">
              <label for="dataCompra" class="form-label">Data da compra</label>
              <input type="date" class="form-control inputModal" name="dataCompra" id="dataEntregaPedido" required>
            </div>

            <div class="mb-3">
              <label for="dataValidade" class="form-label">Data de validaded</label>
              <input type="date" class="form-control inputModal" name="dataValidade" id="dataEntregaPedido" required>
            </div>

          </div>
          <div class="modal-footer modaisCorpos">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
            <button type="submit" class="btn btn-primary" onclick="cadGeral('frmCadIngred','modalCadIngred','cadIngredienteEstoque','listarIngredienteEstoque');"><i class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
          </div>
      </form>
    </div>
  </div>
</div>
<script>
  function formatarNumeroDecimal(input) {
    input.value = input.value.replace(/,/g, '.');
  }
</script>




<script>
  function mostrarAlerta() {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório geral dos Ingredientes?',
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
        window.location.href = `./gerarRelatorios/gerarRelatIngredientes.php`;
      }
    });
  }

  function mostrarAlertaIdGet(idingredientes) {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório do ingrediente?',
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
        window.location.href = './gerarRelatorios/gerarRelatIngredientesUn.php?id=' + idingredientes;
      }
    });
  }

 
</script>

