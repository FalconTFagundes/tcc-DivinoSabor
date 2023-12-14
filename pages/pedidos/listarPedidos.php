<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

?>

<h1 style="text-align: center;">Pedidos</h1>

<!-- btn que chama a modal -->
<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadPedido">
  <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar Pedido
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
        <th scope="col" width="25%">Nome</th>
        <th scope="col" width="15%">Pedido</th>
        <th scope="col" width="30%">Detalhes</th>
        <th scope="col" width="30%">Data de Entrega</th>
        <th scope="col" width="25%">Ações</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!
      $dataSeteDiasAntes = date("Y-m-d", strtotime("-7 days"));


      $retornoListarPedidos = listarGeral('idpedidos, nome, pedido, detalhes, cadastro, alteracao, ativo, dataEntrega', 'pedidos');
      if (is_array($retornoListarPedidos) && !empty($retornoListarPedidos)) {
        foreach ($retornoListarPedidos as $itemPedido) {
          $idPedido = $itemPedido->idpedidos;
          $nomePedido = $itemPedido->nome;
          $pedido = $itemPedido->pedido;
          $detalhesPedido = $itemPedido->detalhes;
          $ativoPedido = $itemPedido->ativo;
          $dataEntrega = $itemPedido->dataEntrega;

          $dataEntregaFormat = date("d/m/Y", strtotime($dataEntrega)); //passando para o formato br

          $classeData = '';
          if (strtotime($dataAtual) >= strtotime($dataEntrega)) {
            $classeData = 'entregaVermelha';
          } elseif (strtotime($dataAtual) >= strtotime('-7 days', strtotime($dataEntrega)) && strtotime($dataAtual) < strtotime($dataEntrega)) {
            $classeData = 'entregaAmarela';
          } else {
            $classeData = 'entregaVerde';
          }

          ?>

          <tr>
            <th scope="row"><?php echo $idPedido; ?></th>
            <td><?php echo $nomePedido; ?></td>
            <td><?php echo $pedido; ?></td>
            <td><?php echo $detalhesPedido; ?></td>
            <td class="<?php echo $classeData; ?>"><?php echo $dataEntregaFormat; ?></td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <?php
                if ($ativoPedido == 'A') {
                ?>
                  <button type='button' class='btn btn-outline-secondary' onclick="ativarGeral(<?php echo $idPedido; ?>,'desativar','ativarPedidos','listarPedidos', 'Pedido marcado como concluído');"> <i class="fa-solid fa-unlock" title="Pedido Não Concluído"></i></i> Não Concluído</button>
                <?php
                } else {
                ?>
                  <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idPedido; ?>, 'ativar', 'ativarPedidos','listarPedidos', 'Pedido marcado como não concluído');"><i class="fa-solid fa-lock" title="Pedido Concluído"></i></i> Concluído</button>

                <?php
                }
                ?>
                <!-- passando id diretamente na URL - sem SEM AJAX -->
                <a href="#" onclick="mostrarAlertaIdGet('<?php echo $idPedido; ?>')">
                  <button type="button" class="btn btn-outline-info btnGerarRelatPedidoUn">
                    <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                  </button>
                </a>
                <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idPedido; ?>', 'excluirPedidos', 'listarPedidos', 'Certeza que deseja excluir?', 'Operação Irreversível!')"><i class="fa-solid fa-trash" title="Excluir"></i> Excluir</button>
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

<!-- Modal Cad Pedido -->
<div class="modal fade" id="modalCadPedido" tabindex="-1" role="dialog" aria-labelledby="modalCadPedido" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:blueviolet; color: white; ">
        <h5 class="modal-title" id="modalCadPedido">Cadastrar Pedido <i class="fa-regular fa-pen-to-square" title="Cadastro de Pedidos"></i></h5>
      </div>
      <form name="frmCadPedido" method="POST" id="frmCadPedido" class="frmCadPedido" action="#">
        <div class="modal-body">
          <div class="form-group">
            <label for="nomePedido" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" name="nomePedido" id="nomePedido" aria-describedby="nomePedido" required>
          </div>
          <div class="form-group">
            <label for="pedido" class="form-label">Pedido</label>
            <input type="text" class="form-control" name="pedido" id="pedido" required>
          </div>
          <div class="form-group">
            <label for="detalhesPedido" class="form-label">Detalhes</label>
            <textarea class="form-control" name="detalhesPedido" id="exampleFormControlTextarea1" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="dataEntregaPedido" class="form-label">Data de Entrega</label>
            <input type="date" class="form-control" name="dataEntregaPedido" id="dataEntregaPedido" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
          <button type="submit" class="btn btn-primary" onclick="cadGeral('frmCadPedido','modalCadPedido','cadastrarPedidos','listarPedidos');"><i class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function mostrarAlerta() {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório geral dos pedidos?',
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
        window.location.href = './gerarRelatorios/gerarRelatPedido.php';
      }
    });
  }

  function mostrarAlertaIdGet(idPedido) {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório do pedido?',
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
        window.location.href = './gerarRelatorios/gerarRelatUnPedido.php?id=' + idPedido;
      }
    });
  }
</script>