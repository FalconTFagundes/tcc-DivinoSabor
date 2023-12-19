<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

?>

<div style="text-align: center;" class="headerCalendar">
<h1>Estoque Ingredientes</h1>
</div>

<!-- btn que chama a modal -->
<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadIngrediente">
  <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar ingrediente
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
        <th scope="col" width="5%">Código</th>
        <th scope="col" width="8%">Imagem</th>
        <th scope="col" width="10%">Nome</th>
        <th scope="col" width="5%">Quantidade</th>
        <th scope="col" width="12%">Peso unitário</th>
        <th scope="col" width="10%">Valor unitário</th>
        <th scope="col" width="10%">Data da compra</th>
        <th scope="col" width="10%">Valor total</th>
        <th scope="col" width="10%">Data de validade</th>
        <th scope="col" width="20%" class="text-center">Ação</th>

      </tr>
    </thead>
    <tbody>



      <?php
      $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!
      $dataSeteDiasAntes = date("Y-m-d", strtotime("-7 days"));

      $retornoListarIngredientes = listarGeral('idingredientes, nomeIngred, img, quantIngred, pesoUnit, precoUnit, dataComp, dataValidad, precoTotal, ativo', 'ingredientes');
      if (is_array($retornoListarIngredientes) && !empty($retornoListarIngredientes)) {
        foreach ($retornoListarIngredientes as $itemIngred) {
          $idIngred = $itemIngred->idingredientes;
          $nomeIngred = $itemIngred->nomeIngred;
          $imgIngred = $itemIngred->img;
          $quantIngred = $itemIngred->quantIngred;
          $pesoUnit = $itemIngred->pesoUnit;
          $precoUnit = $itemIngred->precoUnit;
          $dataComp = $itemIngred->dataComp;
          $precoTotal = $itemIngred->precoTotal;
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
            <td><img src="./assets/images/ingredientes/<?php echo $imgIngred; ?>" alt="Imagem Ingrediente" class="img-thumbnail"></td>
            <td><?php echo $nomeIngred; ?></td>
            <td><?php echo $quantIngred; ?></td>
            <td><?php echo $pesoUnit . " Kg"; ?> </td>
            <td><?php echo $precoUnit . " R$"; ?></td>
            <td><?php echo $dataComp; ?></td>
            <td><?php echo $precoTotal . " R$"; ?></td>
            <td class="<?php echo $classeData; ?>"><?php echo  $dataValidadFormat; ?></td>

            <td>
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <?php
                if ($ativoIngred == 'A') {
                ?>
                  <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idIngred; ?>,'desativar','ativarIngredientes','listarIngredientes', 'Uso suspenso');"> <i class="fa-solid fa-unlock" title="satus ingrediente"></i> Em uso</button>
                <?php
                } else {
                ?>
                  <button type='button' class='btn btn-outline-secondary' onclick="ativarGeral(<?php echo $idIngred; ?>, 'ativar', 'ativarIngredientes','listarIngredientes', 'Ingrediente em uso');"><i class="fa-solid fa-lock" title="Pedido Concluído"></i> Uso suspenso</button>

                <?php
                }
                ?>
                <!-- passando id diretamente na URL - sem SEM AJAX -->
                <a href="#" onclick="mostrarAlertaIdGet('<?php echo $idIngred; ?>')">
                  <button type="button" class="btn btn-outline-info btnGerarRelatIngredientesUn">
                    <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                  </button>
                </a>
                
                <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idIngred ?>', 'excluirIngredientes', 'listarIngredientes', 'Certeza que deseja excluir?', 'Operação Irreversível!')"><i class="fa-solid fa-trash" title="Excluir ingredientes"></i> Excluir </button>
              </div>
            </td>
          </tr>
      <?php }
      } else {
        echo "<div class='alert alert-warning' style='text-align: center;' role='alert'>";
        echo "Nenhum Registro Encontrado";
        echo "</div>";
      }
      ?>
    </tbody>
  </table>

</div>

<!-- Modal Cad Ingrediente -->
<div class="modal fade" id="modalCadIngrediente" tabindex="-1" role="dialog" aria-labelledby="modalCadIngred" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:blueviolet; color: white; ">
        <h5 class="modal-title" id="modalCadIngrediente">Cadastrar Ingrediente <i class="fa-regular fa-pen-to-square" title="Cadastro de Pedidos"></i></h5>
      </div>
      <form name="frmCadIngrediente" method="POST" id="frmCadIngrediente" class="frmCadIngrediente" action="#">
        <div class="modal-body modaisCorpos">
          <div class="form-group">
            <label for="nomeIngred" class="form-label">Ingrediente</label>
            <input type="text" class="form-control inputModal" name="nomeIngred" id="nomeIngred">
          </div>
          <div class="form-group">
            <label for="imgIngrediente" class="form-label">Imagem Ingrediente</label>

          </div>
          <div class="form-group">
            <!-- só aparece quando o btn type file for desabilitado no javascript (quando a outra img aparecer - img já cadastrada) -->
            <div id="avisoDesabilitar" class="alert alert-warning" style="display:none; text-align: center;">
              Upload desativado - Imagem já Exibida
            </div>

            <input type="file" class="form-control inputModal" name="imgIngrediente" id="imgIngrediente">
          </div>
          <div id="previewUploadIngrediente"></div>
          <div class="form-group">
            <label for="codigoIngrediente" class="form-label">Código do Ingrediente</label>
            <input type="text" class="form-control inputModal" name="codigoIngrediente" id="codigoIngrediente" required>
          </div>
          <div class="form-group">
            <button type="button" class="btn btn-outline-warning" id="btnConsultIngredientes">Consultar</button>
          </div>
          <div class="form-group">
            <label for="quantIngred" class="form-label">Quantidade</label>
            <input type="number" class="form-control inputModal" name="quantIngred" id="quantidade" required>
          </div>

          <div class="form-group">
            <label for="pesoIngred" class="form-label">Peso Unitário</label>
            <input type="text" step=".01" class="form-control inputModal" name="pesoIngred" id="pesoIngred" oninput="formatarNumeroDecimal(this)">
          </div>

          <div class="form-group">
            <label for="valorIngred" class="form-label">Valor Unitário</label>
            <input type="text" step=".01" class="form-control inputModal" name="valorIngred" id="valorIngred" oninput="formatarNumeroDecimal(this)">
          </div>

          <div class="mb-3">
            <label for="dataCompra" class="form-label">Data da compra</label>
            <input type="date" class="form-control inputModal" name="dataCompra" id="dataCompra">
          </div>

          <div class="mb-3">
            <label for="dataValidade" class="form-label">Data de validade</label>
            <input type="date" class="form-control inputModal" name="dataValidade" id="dataValidade">
          </div>
        </div>
        <div class="modal-footer modaisCorpos">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
          <button type="submit" class="btn btn-primary" onclick="cadIngredientesUpload('frmCadIngrediente');"><i class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function formatarNumeroDecimal(input) {
    input.value = input.value.replace(/,/g, '.');
  }



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
        window.location.href = './gerarRelatorios/gerarRelatUnIngredientes.php?id=' + idingredientes;
      }
    });
  }
</script>