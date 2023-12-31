  <?php
  include_once './config/constantes.php';
  include_once './config/conexao.php';
  include_once './func/dashboard.php';

  ?>

  <div style="text-align: center;" class="headerCalendar">
    <h1>Clientes</h1>
  </div>

  <!-- btn que chama a modal -->
  <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#modalCadClientes">
    <i class="fa-solid fa-plus" title="Cadastrar"></i> Cadastrar Clientes
  </button>


  <button type="button" class="btn btn-outline-secondary" onclick="mostrarAlerta();">
    <i class="fa-solid fa-print" title="Gerar Relatório"></i>
    Gerar Relatório Geral
  </button>

  <br><br>
  <i class="fa-solid fa-magnifying-glass fa-lg"></i>
  <label for="inputSearch" class="labelSearch"><h5>Pesquisar Clientes</h5></label>
  <div class="input-group input-group-sm mb-3">
    <input type="text" id="buscarCliente" class="form-control inputSearch" aria-label="Small" placeholder="Pesquise">
  </div>


  <table class="table-financeira table table-hover" id="tabelaClientes">
    <thead>
      <tr>
        <th scope="col" width="5"><i class="fa-solid fa-id-badge"></i> Código</th>
        <th scope="col" width="15%"><i class="fa-solid fa-user"></i> Nome</th>
        <th scope="col" width="20%"><i class="fa-solid fa-location-dot"></i> Endereço</th>
        <th scope="col" width="10%"><i class="fa-solid fa-house"></i> Complemento</th>
        <th scope="col" width="10%"><i class="fa-solid fa-flag"></i> Estado</th>
        <th scope="col" width="15%"><i class="fa-solid fa-location-crosshairs"></i> Cidade</th>
        <th scope="col" width="5%"><i class="fa-solid fa-phone"></i> Telefone</th>
        <th scope="col" width="10%"><i class="fa-solid fa-pen-to-square"></i> Ações</th>
        <!-- CEP consta somente no relatório!! -->
      </tr>
    </thead>
    <tbody>

      <?php
      $dataAtual = date("Y-m-d");  // Formato ISO 8601!!!!!!

      $retornoListarClientes = listarGeral('idclientes, nome, endereco, complemento, cidade, estado, cep, telefone, cadastro, alteracao, ativo', 'clientes');
      if (is_array($retornoListarClientes) && !empty($retornoListarClientes)) {
        foreach ($retornoListarClientes as $itemCliente) {
          $idCliente = $itemCliente->idclientes;
          $nomeCliente = $itemCliente->nome;
          $enderecoCliente = $itemCliente->endereco;
          $complementoCliente = $itemCliente->complemento;
          $estadoCliente = $itemCliente->estado;
          $cidadeCliente = $itemCliente->cidade;
          $cepCliente = $itemCliente->cep;
          $telefoneCliente = $itemCliente->telefone;
          $cadastroCliente = $itemCliente->cadastro;
          $ativoCliente = $itemCliente->ativo;
      ?>

          <tr>
            <td scope="row"><?php echo $idCliente; ?></td>
            <td><?php echo $nomeCliente; ?></td>
            <td><?php echo $enderecoCliente; ?></td>
            <td><?php echo $complementoCliente; ?></td>
            <td><?php echo $estadoCliente; ?></td>
            <td><?php echo $cidadeCliente; ?></td>
            <td><?php echo $telefoneCliente; ?></td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <?php
                if ($ativoCliente == 'A') {
                ?>
                  <button type='button' class='btn btn-outline-success' onclick="ativarGeral(<?php echo $idCliente; ?>,'desativar','ativarClientes','listarClientes', 'Cliente Desativado com Sucesso');"> <i class="fa-solid fa-unlock" title="Cliente Ativado"></i> Ativado</button>
                <?php
                } else {
                ?>
                  <button type='button' class='btn btn-outline-warning' onclick="ativarGeral(<?php echo $idCliente; ?>, 'ativar', 'ativarClientes','listarClientes', 'Cliente Ativado com Sucesso');"><i class="fa-solid fa-lock" title="Cliente Não Ativado"></i> Desativado</button>

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


  <!-- Modal Cad Cliente -->
  <div class="modal fade" id="modalCadClientes" tabindex="-1" role="dialog" aria-labelledby="modalCadPedido" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color:blueviolet; color: white; ">
          <h5 class="modal-title" id="modalCadPedido">Cadastrar Cliente <i class="fa-solid fa-user-plus" title="Cadastro de Clientes"></i></h5>
        </div>
        <form name="frmCadClientes" method="POST" id="frmCadClientes" class="frmCadClientes" action="#">
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
              <label for="estadoCliente" class="form-label">Estado</label>
              <input type="text" class="form-control inputModal" name="estadoCliente" id="estadoCliente" required>
            </div>
            <div class="mb-3">
              <label for="cidadeCliente" class="form-label">Cidade</label>
              <input type="text" class="form-control inputModal" name="cidadeCliente" id="cidadeCliente" required>
            </div>
            <div class="mb-3">
              <label for="cepCliente" class="form-label">CEP</label>
              <input type="text" class="form-control inputModal maskCEP" name="cepCliente" id="cepCliente" required>
            </div>
            <div class="mb-3">
              <label for="telefoneCliente" class="form-label">Telefone</label>
              <input type="text" class="form-control inputModal maskTelefone" name="telefoneCliente" id="telefoneCliente" required>
            </div>
          </div>
          <div class="modal-footer modaisCorpos">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa-solid fa-xmark" title="Fechar Modal"></i> Fechar</button>
            <button type="submit" class="btn btn-primary" id="btnCadClientes" onclick="cadGeral('frmCadClientes','modalCadClientes','cadastrarClientes','listarClientes');"><i class="fa-solid fa-check" title="Cadastrar Pedido"></i> Cadastrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function mostrarAlerta() {
      Swal.fire({
        title: 'Você tem certeza?',
        text: 'Deseja gerar o relatório geral dos clientes?',
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
          window.location.href = './gerarRelatorios/gerarRelatClientes.php';
        }
      });
    }

    function mostrarAlertaIdGet(id) {
      Swal.fire({
        title: 'Você tem certeza?',
        text: 'Deseja gerar o relatório do cliente?',
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
          window.location.href = './gerarRelatorios/gerarRelatUnCliente.php?id=' + id;
        }
      });
    }

    function buscarNomeCliente(nome) {
      $.ajax({
        url: "pesquisarCliente.php",
        method: "POST",
        data: {
          nome: nome
        },
        success: function(data) {
          // atualiza o conteúdo da tabela com os resultados da pesquisa
          $('#tabelaClientes tbody').html(data);
        }
      });
    }

    $(document).ready(function() {
      // clientes iniciais
      buscarNomeCliente();

      // atualiza os clientes conforme o usuário digita na barra de pesquisa
      $('#buscarCliente').keyup(function() {
        var nome = $(this).val();
        if (nome != '') {
          buscarNomeCliente(nome);
        } else {
          // se pesquisa estiver vazia, exibe todos os clientes novamente
          buscarNomeCliente();
        }
      });
    });
  </script>