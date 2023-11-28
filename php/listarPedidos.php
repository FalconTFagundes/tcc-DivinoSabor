<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$listar = pedidos();

?>

<div class="card mt-3">
    <div class="card-header bg-p2 text-white">
        <h4><span class="mdi mdi-account-group"></span> Lista de Pagamentos
            <button type="button" class="btn btn-p2 text-white float-right" data-toggle="modal" data-target="#modalAddPag">Adicionar Registros <span class="mdi mdi-plus"></span></button>
        </h4>
    </div>
    <div class="card-body">
        <table class="table table-hover text-center">
            <thead class="bg-p2 text-white">
                <tr>
                    <th scope="col" width="5%"><span class="mdi mdi-cat"></span> ID</th>
                    <th scope="col" width="25%"><span class="mdi mdi-image"></span></span>
                        Arte</th>
                    <th scope="col" width="10%"><span class="mdi mdi-cash"></span> Titulo</th>
                    <th scope="col" width="10%"><span class="mdi mdi-cash"></span> Comprador</th>
                    <th scope="col" width="10%"><span class="mdi mdi-cash"></span> Vendedor</th>
                    <th scope="col" width="20%"><span class="mdi mdi-cash"></span> Descrição</th>
                    <th scope="col" width="10%"><span class="mdi mdi-cash"></span> Valor</th>
                    <th scope="col" width="10%"><span class="mdi mdi-cash-register"></span> Tipo de Pagamento</th>
                    <th scope="col" width="5%"><span class="mdi mdi-alert-circle-outline"></span> Status</th>
                    <th scope="col" width="10%"><span class="mdi mdi-view-dashboard-edit"></span> Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if ($listar == 'Vazio') {

                ?>

                    <tr>
                        <td colspan="5">
                            <div class="alert alert-danger text-center" role="alert">Não há registros no banco!</div>
                        </td>
                    </tr>

                    <?php

                } else {

                    foreach ($listar as $listarPedido) {
                        $idV = $listarPedido->idvendedor;
                        $idpedido = $listarPedido->idpedidosarte;
                        $img = $listarPedido->imgarte;
                        $tituloA = $listarPedido->titulo;
                        $nomeC = $listarPedido->nome;
                        $descPedido = $listarPedido->descPedido;
                        $valor = $listarPedido->valor;
                        $tipopag = $listarPedido->tipopag;
                        $ativo = $listarPedido->ativo;

                        $listar2 = vendedorNome($idV);

                        if ($listar2 == 'Vazio') {

                    ?>

                            <tr>
                                <td colspan="5">
                                    <div class="alert alert-danger text-center" role="alert">Não há registros no banco!</div>
                                </td>
                            </tr>

                            <?php

                        } else {

                            foreach ($listar2 as $listarVend) {
                                $nomeVend = $listarVend->nome;

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $idpedido; ?></th>
                                    <td class="imgArtista"><img src="./img/artesArtista/<?php echo $img; ?>" alt=""></td>
                                    <td><?php echo $tituloA; ?></td>
                                    <td><?php echo $nomeC; ?></td>
                                    <td><?php echo $nomeVend; ?></td>
                                    <td><?php echo $descPedido; ?></td>
                                    <td><?php echo $valor; ?></td>
                                    <td><?php echo $tipopag; ?></td>
                                    <td><?php echo $ativo; ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <?php

                                            if ($ativo === 'A') {

                                            ?>

                                                <button type="button" class="btn btn-danger" onclick="ativGeral(<?php echo $id; ?>, 'ativarPag', 'listarPag');">Desativar <span class="mdi mdi-lock"></span></button>

                                            <?php

                                            } elseif ($ativo === 'D') {

                                            ?>

                                                <button type="button" class="btn btn-success" onclick="ativGeral(<?php echo $id; ?>, 'ativarPag', 'listarPag');">Ativar <span class="mdi mdi-lock-open"></span></button>

                                            <?php

                                            } else {

                                            ?>

                                                <button type="button" class="btn btn-warning disabled">Erro <span class="mdi mdi-alert"></span></button>

                                            <?php
                                            }


                                            ?>
                                            <button type="button" class="btn btn-p2" onclick="dataPag(<?php echo $id; ?>, 'modalAltPag');">Alterar <span class="mdi mdi-pencil"></span></button>



                                            <button type="button" class="btn btn-danger" onclick="msgDelete(<?php echo $id; ?>, 'excPag', 'listarPag');">Excluir <span class="mdi mdi-delete"></span></button>


                                        </div>
                                    </td>
                                </tr>
                <?php

                            }
                        }
                    }
                }

                ?>
            </tbody>
        </table>
    </div>
</div>


<!-- modal de cadastro começa aqui uau uau uau -->

<div class="modal fade" id="modalAddPag" tabindex="-1" role="dialog" aria-labelledby="modalAddPag" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-p3 text-white">
                <h5 class="modal-title" id="cadastrarPagModal"><span class="mdi mdi-account-plus"></span> Cadastrar Novo Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <form id="frmAddPag" name="frmAddPag" method="post" action="#">

                <div class="modal-body">

                    <div class="form-group">
                        <label for="valorPag"><span class="mdi mdi-cash"></span> Valor do Pagamento:</label>
                        <input type="text" class="form-control form-control-sm maskValor" id="valorPag" name="valorPag" placeholder="Insira o valor..." required maxlength="8">
                    </div>

                    <div class="form-group">
                        <label for="tipoPagPag"><span class="mdi mdi-cash-register"></span> Tipo de Pagamento:</label>

                        <?php

                        $listarTp = listarGeral('idtipopag, tipopag', 'tbtipopag');

                        if ($listarTp == 'Vazio') {

                        ?>

                            <div class="alert alert-danger text-center" role="alert">Não há registros no banco!</div>

                        <?php

                        } else {

                        ?>

                            <select class="form-control" name="tipoPagPag" id="tipoPagPag">

                                <?php

                                foreach ($listarTp as $listarItemTp) {
                                    $idTp = $listarItemTp->idtipopag;
                                    $tpF = $listarItemTp->tipopag;

                                ?>

                                    <option value="<?php echo $idTp; ?>"><?php echo $tpF; ?></option>

                                <?php

                                }

                                ?>

                            </select>
                        <?php

                        }

                        ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="mdi mdi-close"></span> Cancelar</button>
                        <button type="submit" class="btn btn-success" onclick="addPag();"><span class="mdi mdi-account-plus"></span> Cadastrar</button>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>

<!-- modal de cadastro termina aqui uau uau uau uau uau -->


<!-- //////////////////// -->


<!-- modal de alterar começa aqui uau uau uau -->

<div class="modal fade" id="modalAltPag" tabindex="-1" role="dialog" aria-labelledby="modalAltPag" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border border-secondary">
            <div class="modal-header bg-p3 text-white">
                <h5 class="modal-title" id="exampleModalLongTitle"><span class="mdi mdi-pencil"></span> Alterar Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <form id="frmAltPag" name="frmAltPag" method="post" action="#">

                <div class="modal-body">

                    <div class="form-group">
                        <label for="pagAlt"><span class="mdi mdi-cash-register"></span> Tipo de Pagamento:</label>
                        <input type="text" class="form-control form-control-sm" id="pagAlt" name="pagAlt" placeholder="Insira seu nome..." required>
                    </div>

                    <input type="hidden" value="" id="inputAltPag" name="inputAltPag">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="mdi mdi-close"></span> Cancelar</button>
                        <button type="submit" class="btn btn-success" onclick="altPag();"><span class="mdi mdi-account-plus"></span> Alterar</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<!-- modal de alterar termina aqui uau uau uau uau uau -->