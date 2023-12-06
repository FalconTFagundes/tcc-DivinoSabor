<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';
?>
<h1 style="text-align: center;">Pedidos</h1>


<button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalCadPedido">
  Cadastrar Pedido
</button>
<br><br>



<table class="table-financeira table table-hover">
  <thead>
    <tr>
      <th scope="col" width="5%">Código</th>
      <th scope="col" width="25%">Nome</th>
      <th scope="col" width="15%">Status</th>
      <th scope="col" width="30%">Detalhes</th>
      <th scope="col" width="25%">Ações</th>
    </tr>
  </thead>
  <?php
  $retornoListarPedidos = listarGeral('idpedidos, nome, status, detalhes, cadastro, alteracao, ativo', 'pedidos');
  if(empty($retornoListarPedidos)){
    echo 'vazio';
  }
  if (!empty($retornoListarPedidos))   {
    foreach ($retornoListarPedidos as $itemPedido) {
      $idPedido = $itemPedido->idpedidos;
      $nomePedido = $itemPedido->nome;
      $statusPedido = $itemPedido->status;
      $detalhesPedido = $itemPedido->detalhes;

  ?>
      <tbody>

        <tr>
          <th scope="row"><?php echo $idPedido; ?></th>
          <td><?php echo $nomePedido; ?></td>
          <td><?php echo $statusPedido; ?></td>
          <td><?php echo $detalhesPedido; ?></td>
          <td>
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <button type="button" class="btn btn-success">Ativar</button>
              <button type="submit" class="btn btn-danger" onclick="excGeral('<?php echo $idPedido; ?>', 'excluirPedido', 'listarPedidos', 'Certeza que deseja excluir?', 'Operação Irreversível!')">Excluir</button>
            </div>
          </td>

        </tr>

      </tbody>
  <?php
    }
  }
 ?>
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