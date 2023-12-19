<?php

include_once "./config/constantes.php";
include_once "./config/conexao.php";
include_once "./func/dashboard.php";

?>

<div class="headerCardBox">
    <h2>Painel Financeiro</h2>
</div>

<button type="button" id="btnRelatFinanceiro" class="btn" onclick="mostrarAlerta();">
  <i class="fa-solid fa-print" title="Gerar Relatório"></i>
  Gerar Relatório Geral
</button>

<div class="cardBox">

    <div class="card">
        <?php
        $retornoQtdClientes = listarGeralCount('nome', 'clientes');
        ?>
        <div>
            <!-- minha funct retorna a quantidade de registros encontrados -->
            <div class="numbers"><?php echo $retornoQtdClientes['quantidade']; ?></div>
            <div class="cardName">Clientes</div>
        </div>

        <div class="iconBox">
            <i class="fa-regular fa-user"></i>
        </div>
    </div>

    <?php
    $retornoSomaVendas = somarGeral('valorPacote', 'pacoteCadastro');
    ?>

    <div class="card">
        <div>
            <div class="numbers"><?php echo number_format($retornoSomaVendas, 0, ',', '.') . " R$"; ?></div>
            <div class="cardName">Vendas mensais</div>
        </div>


        <div class="iconBox">
            <i class="fa-regular fa-money-bill-1"></i>
        </div>
    </div>

    <?php
    $retornoDefictProdutos = somarGeral('valor', 'produtos');
    $retornoDefictPacotes = somarGeral('valorPacote', 'pacotecadastro');
    $retornoDiferencaDefict = $retornoDefictPacotes - $retornoDefictProdutos;
    ?>

    <div class="card">
        <div>
            <div class="numbers"><?php echo $retornoDiferencaDefict . " R$"; ?></div>
            <div class="cardName">Défict</div>
        </div>

        <div class="iconBox">
            <i class='bx bx-line-chart-down'></i>
        </div>
    </div>

    <?php
    $lucro = $retornoSomaVendas - $retornoDiferencaDefict;
    ?>
    <div class="card">
        <div>
            <div class="numbers"><?php echo $lucro . " R$"; ?></div>
            <div class="cardName">Lucro</div>
        </div>

        <div class="iconBox">
            <i class="fa-solid fa-chart-line"></i>
        </div>
    </div>
</div>



<!-- GRÁFICOS -->

<!-- <div class="graficoBox">
    <div class="box">
        <canvas id="grafico2"></canvas>
    </div>
    <div class="box"><canvas id="grafico1"></canvas></div>
</div> -->

<!-- FIM GRÁFICOS -->

<div class="details">

    <div class="containerVendasRecentes">
        <!-- ORDENS RECENTES -->
        <div class="headerTableFin">
            <h2>Vendas recentes</h2>
            <div class="cardHeader">
                <a href="" class="btn linkMenu" idMenu="listarPacotes">Ver tudo</a>
            </div>
        </div>

        <div>

            <?php $retornoUltimasVendas = listarGeralPacoteInnerjoinFinanceiro(); ?>
            <table class="vendasRecentes">
                <thead>
                    <tr>
                        <th scope="col" width="5%">Código do pedido</th>
                        <th scope="col" width="25%">Nome do cliente</th>
                        <th scope="col" width="25%">Pacote escolhido</th>
                        <th scope="col" width="25%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($retornoUltimasVendas as $itemUltimasVendas) {
                        $idPedidoUltimasVendas = $itemUltimasVendas->idpacote;
                        $nomeClienteUltimasVendas = $itemUltimasVendas->nome;
                        $pacoteEscolhidoultimasVendas = $itemUltimasVendas->pacote;
                        $statusUltimasVendas = $itemUltimasVendas->ativo;
                    ?>
                        <tr>
                            <th>
                                <p><?php echo $idPedidoUltimasVendas; ?></p>
                            </th>
                            <th>
                                <p><?php echo $nomeClienteUltimasVendas; ?></p>
                            </th>
                            <th>
                                <p><?php echo $pacoteEscolhidoultimasVendas; ?></p>
                            </th>
                            <th>
                                <?php
                                if ($statusUltimasVendas == 'A') {
                                ?>
                                    <span class="status concluido">Ativado</span>
                                <?php
                                } else {
                                ?>
                                    <span class="status emAndamento">Desativado</span>
                                <?php
                                }
                                ?>

                            </th>
                        </tr>
                    <?php  } ?>
                </tbody>
            </table>
        </div>
        <!-- FIM ORDENS RECENTES -->
    </div>

    <?php
    $ultimosClientes = obterUltimosClientes(); //capturando os três ultimos clientes cadastrados no banco
    ?>

    <div class="containerClientesRecentes">
        <div class="headerTableFin">
            <h2>Clientes recentes</h2>
            <div class="cardHeader">
                <a href="" class="btn linkMenu" idMenu="listarClientes">Ver tudo</a>
            </div>
        </div>

        <div>
            <table class="clientesRecentes">
                <thead>
                    <tr>
                        <th scope="col" width="5%">Código do cliente</th>
                        <th scope="col" width="25%">Nome do cliente</th>
                        <th scope="col" width="25%">Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($ultimosClientes as $cliente) : ?>
                        <tr>
                            <th>
                                <p><?php echo $cliente['idclientes']; ?></p>
                            </th>
                            <th>
                                <p><?php echo $cliente['nome']; ?></p>
                            <th>
                                <?php
                                if ($cliente['ativo'] == 'A') {
                                    echo '<span class="status concluido">Ativo</span>';
                                } else {
                                    echo '<span class="status emAndamento">Inativo</span>';
                                }
                                ?>
                            </th>
                        </tr>
                    <?php endforeach; ?> <!-- finalizando o loop aq baby ;) -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$_SESSION['dados_painel_financeiro'] = [
    'qtdClientes' => $retornoQtdClientes['quantidade'],
    'vendasMensais' => number_format($retornoSomaVendas, 0, ',', '.') . " R$",
    'deficit' => $retornoDiferencaDefict . " R$",
    'lucro' => $lucro . " R$",
    'ultimasVendas' => $retornoUltimasVendas,
    'ultimosClientes' => $ultimosClientes,
];
?>

<script>
     function mostrarAlerta() {
    Swal.fire({
      title: 'Você tem certeza?',
      text: 'Deseja gerar o relatório financeiro geral?',
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
        window.location.href = `./gerarRelatorios/gerarRelatFinanceiro.php`;
      }
    });
  }
</script>