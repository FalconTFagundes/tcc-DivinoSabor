<?php
include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

?>

<h1 style="text-align: center;">Clientes</h1>

<!-- btn que chama a modal -->
<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadClientes">
  <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar Clientes
</button>


<button type="button" class="btn btn-outline-secondary" onclick="mostrarAlerta();">
  <i class="fa-solid fa-print"  title="Gerar Relatório"></i>
  Gerar Relatório Geral
</button>


<br><br>



<div style="height: 400px;">
  <table class="table-financeira table table-hover">
    <thead>
      <tr>
        <th scope="col" width="5%">Código</th>
        <th scope="col" width="10%">Nome</th>
        <th scope="col" width="25%">Endereço</th>
        <th scope="col" width="10%">Complemento</th>
        <th scope="col" width="20%">Cidade</th>
        <th scope="col" width="20%">Telefone</th>
        <th scope="col" width="25%">Ação</th>
                <!-- CEP consta somente no relatório!! -->
      </tr>
    </thead>
    <tbody>

      <?php
      $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!

      $retornoListarClientes = listarGeral('idclientes, nome, endereco, complemento, cidade, cep, telefone, cadastro, alteracao, ativo', 'clientes');
      if (is_array($retornoListarClientes) && !empty($retornoListarClientes)) {
        foreach ($retornoListarClientes as $itemCliente) {
            $idCliente = $itemCliente -> idclientes;
            $nomeCliente = $itemCliente -> nome;
            $enderecoCliente = $itemCliente -> endereco;
            $complementoCliente = $itemCliente -> complemento;
            $cidadeCliente = $itemCliente -> cidade;
            $cepCliente = $itemCliente -> cep;
            $telefoneCliente = $itemCliente -> telefone;
            $cadastroCliente = $itemCliente -> cadastro;
            $ativoCliente = $itemCliente -> ativo;  
          ?>

          <tr>
            <th scope="row"><?php echo $idCliente; ?></th>
            <td><?php echo $nomeCliente; ?></td>
            <td><?php echo $enderecoCliente; ?></td>
            <td><?php echo $complementoCliente; ?></td>
            <td><?php echo $cidadeCliente; ?></td>
            <td><?php echo $telefoneCliente; ?></td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <?php
                if ($ativoCliente == 'A') {
                ?>
                  <button type='button' class='btn btn-outline-secondary' onclick="ativarGeral(<?php echo $idCliente; ?>,'desativar','ativarClientes','listarClientes', 'Cliente Ativado com Sucesso');"> <i class="fa-solid fa-unlock" title="Cliente Não Ativado"></i></i>Ativado</button>
                <?php
                } else {
                ?>
                  <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idCliente; ?>, 'ativar', 'ativarClientes','listarClientes', 'Cliente Desativado com Sucesso');"><i class="fa-solid fa-lock" title="Cliente Ativado"></i></i>Desativado</button>

                <?php
                }
                ?>
                <!-- passando id diretamente na URL - sem SEM AJAX -->

                <a href="#" onclick="mostrarAlertaIdGet('<?php echo $idCliente; ?>')">
                  <button type="button" class="btn btn-outline-info">
                    <i class="fa-solid fa-print" title="Gerar Relatório"></i> Relatório
                  </button>
                </a>
                <button type="submit" class="btn btn-outline-danger" onclick="excGeral('<?php echo $idCliente; ?>', 'excluirClientes', 'listarClientes', 'Certeza que deseja excluir este Cliente?', 'Operação Irreversível!')"><i class="fa-solid fa-trash" title="Excluir"></i> Excluir</button>
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
<div class="modal fade" id="modalCadClientes" tabindex="-1" role="dialog" aria-labelledby="modalCadPedido" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:blueviolet; color: white; ">
        <h5 class="modal-title" id="modalCadPedido">Cadastrar Cliente <i class="fa-solid fa-user-plus" title="Cadastro de Clientes"></i></h5>
      </div>
      <form name="frmCadPedido" method="POST" id="frmCadPedido" class="frmCadPedido" action="#">
        <div class="modal-body modaisCorpos">
          <div class="form-group">
            <label for="nomeCliente" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control inputModal" name="nomeCliente" id="nomeCliente" aria-describedby="nomeCliente" required>
          </div>
          <div class="form-group">
            <label for="enderecoCliente" class="form-label">Endereço</label>
            <input type="text" class="form-control inputModal" name="enderecoCliente" id="enderecoCliente" required>
          </div>
          <div class="form-group">
            <label for="complementoCliente" class="form-label">Complemento</label>
            <input type="text" class="form-control inputModal" name="complementoCliente" id="complementoCliente" required>
          </div>
          <div class="mb-3">
            <label for="cidadeCliente" class="form-label">Cidade</label>
            <input type="date" class="form-control inputModal" name="cidadeCliente" id="cidadeCliente" required>
          </div>
          <div class="mb-3">
            <label for="cepCliente" class="form-label">CEP</label>
            <input type="number" class="form-control inputModal" name="cepCliente" id="cepCliente" required>
          </div>
          <div class="mb-3">
            <label for="telefoneCliente" class="form-label">Telefone</label>
            <input type="text" class="form-control inputModal" name="telefoneCliente" id="telefoneCliente" required>
          </div>
        </div>
        <div class="modal-footer modaisCorpos">
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