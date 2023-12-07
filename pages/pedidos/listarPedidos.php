<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';
?>
<h1 style="text-align: center;">Pedidos</h1>


<button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalCadPedido">
<i class="fa-solid fa-plus"></i> Cadastrar Pedido
</button>
<br><br>



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
    $retornoListarPedidos = listarGeral('idpedidos, nome, pedido, detalhes, cadastro, alteracao, ativo, dataEntrega', 'pedidos');
    if (is_array($retornoListarPedidos) && !empty($retornoListarPedidos)) {
      foreach ($retornoListarPedidos as $itemPedido) {
        $idPedido = $itemPedido->idpedidos;
        $nomePedido = $itemPedido->nome;
        $pedido = $itemPedido->pedido;
        $detalhesPedido = $itemPedido->detalhes;
        $ativoPedido = $itemPedido-> ativo;
        $dataEntrega = $itemPedido -> dataEntrega;
        $dataEntregaFormat = date("d/m/Y", strtotime($dataEntrega));
    ?>

        <tr>
          <th scope="row"><?php echo $idPedido; ?></th>
          <td><?php echo $nomePedido; ?></td>
          <td><?php echo $pedido; ?></td>
          <td><?php echo $detalhesPedido; ?></td>
          <td class="dataValidade"><?php echo $dataEntregaFormat; ?></td>
          <td>
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <?php
              if ($ativoPedido == 'A') {
              ?>
                <button type='button' class='btn btn-outline-dark' onclick="ativarGeral(<?php echo $idPedido;?>,'desativar','ativarPedidos','listarPedidos');"> <i class="fa-solid fa-unlock"></i> Não Concluído</button>
              <?php
              } else {
              ?>
                <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idPedido; ?>, 'ativar', 'ativarPedidos','listarPedidos');"><i class="fa-solid fa-lock"></i> Concluído</button>

              <?php
              }
              ?>
              <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idPedido; ?>', 'excluirPedidos', 'listarPedidos', 'Certeza que deseja excluir?', 'Operação Irreversível!')"><i class="fa-solid fa-trash"></i> Excluir</button>
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



<div class="modal fade" id="modalCadPedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:blueviolet; color: white;">
        <h5 class="modal-title" id="exampleModalLabel">Cadastrar Pedido</h5>
      </div>
      <form name="frmCadPedido" method="POST" id="frmCadPedido" class="frmCadPedido" action="#">
        <div class="modal-body">
          <div class="mb-3">
            <label for="nomePedido" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" name="nomePedido" id="nomePedido" aria-describedby="nomePedido">
          </div>
          <div class="mb-3">
            <label for="statusPedido" class="form-label">Status do Pedido</label>
            <input type="text" class="form-control" name="statusPedido" id="statusPedido">
          </div>
          <div class="mb-3">
            <label for="detalhesPedido" class="form-label">Detalhes</label>
            <input type="text" class="form-control" name="detalhesPedido" id="detalhesPedido">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary" onclick="cadGeral('frmCadPedido','modalCadPedido','cadastrarPedidos','listarPedidos');">Cadastrar</button>
        </div>
      </form>
    </div>
  </div>
</div>