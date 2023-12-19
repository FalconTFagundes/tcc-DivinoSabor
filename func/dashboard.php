<?php

/**
 * Created by PhpStorm.
 * User: Luciano Pettersen
 * Date: 04/05/2020
 * Time: 15:03
 */
?>
<?php

//-------------------------------------------SESSÃO---------------------------------------------------------------------
//validar Sessao usuário


function nomeCorParaHex($nomeCor)
{
    $cores = [
        'roxo' => '#9E77F1',
        'amarelo' => '#D4C200',
        'azul' => '#297BFF',
        'vermelho' => '#FF0831',
        'verde' => '#00BD3f',
    ];
    return isset($cores[$nomeCor]) ? $cores[$nomeCor] : '#000000';
}

function obterOpcoesDoBanco($tabela, $idColuna, $nomeColuna)
{
    try {
        $conn = conectar();

        $consulta = "SELECT $idColuna, $nomeColuna FROM $tabela;";

        $sqlLista = $conn->query($consulta);
        $opcoes = $sqlLista->fetchAll(PDO::FETCH_ASSOC);

        return $opcoes ?: 'Vazio';
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return null;
    } finally {
        if ($conn) {
            $conn = null;
        }
    }
}



function obterPacotes()
{
    try {
        $conn = conectar();

        $consulta = "SELECT
            pacote.pacote,
            MAX(pacote.idpacote) AS idpacote,
            MAX(pacote.qtdPessoas) AS qtdPessoas,
            MAX(pacotecadastro.valorPacote) AS valorPacote,
            MAX(pacotecadastro.detalhes) AS detalhes,
            MAX(pacote.ativo) AS AtivoPacoteCadastro,
            MAX(pacotecadastro.cadastro) AS cadastro,
            MAX(pacotecadastro.alteracao) AS alteracao
          FROM pacote
          INNER JOIN pacotecadastro ON pacote.idpacote = pacotecadastro.idpacote
          GROUP BY pacote.pacote;";

        $sqlLista = $conn->query($consulta);
        $pacotes = $sqlLista->fetchAll(PDO::FETCH_ASSOC);

        return $pacotes ?: 'Vazio';
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return null;
    } finally {
        if ($conn) {
            $conn = null;
        }
    }
}

function obterUltimosClientes()
{
    try {
        $conn = conectar();

        $consulta = "SELECT idclientes, nome, ativo, cadastro
                    FROM clientes
                    WHERE ativo = 'A'
                    ORDER BY cadastro DESC
                    LIMIT 3";

        $sqlLista = $conn->query($consulta);
        $clientes = $sqlLista->fetchAll(PDO::FETCH_ASSOC);

        return $clientes ?: 'Vazio';
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return null;
    } finally {
        if ($conn) {
            $conn = null;
        }
    }
}




function checarLogin($tabela, $valor1, $valor2)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT email, senha FROM $tabela WHERE email = ? AND senha = ?");
        $sqlLista->bindValue(1, $valor1, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $valor2, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return 'OK';
        } else {
            return 'false';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}


function validarSessao($redirecionar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        if (isset($_SESSION['idpessoa']) && !empty($_SESSION['idpessoa']) && is_numeric($_SESSION['idpessoa'])) {
            $idAdminPessoa = $_SESSION['idpessoa'];
            $sqlLista = $conn->prepare("SELECT idpessoa, nome, avatar, email, tipousuario "
                . "FROM pessoa "
                . "WHERE idpessoa = ? AND ativo = ?");
            $sqlLista->bindValue(1, $idAdminPessoa, PDO::PARAM_INT);
            $sqlLista->bindValue(2, 'A', PDO::PARAM_STR);
            $sqlLista->execute();
            $conn->commit();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'Vazio';
            };
        } else {
            destruirSessaoRedirecionar("$redirecionar");
            exit();
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//validar Acesso a pagina Externa
function validarSessaoExterna($redirecionar)
{
    if (!isset($_SESSION['idsis']) && empty($_SESSION['idsis'])) {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        };
        session_destroy();
        header("Location: $redirecionar");
        exit();
    };
}

//validar Sessao usuário
function validarNivelAcesso($idNivel, $idSis)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        if (is_numeric($idNivel) && is_numeric($idSis)) {
            $sqlLista = $conn->prepare("SELECT idnivel, idsis "
                . "FROM nivelacesso "
                . "WHERE idnivel = '1' AND idsis = ? AND ativo = 'A' OR idnivel IN (?) AND idsis = ? "
                . " AND ativo = 'A'");
            $sqlLista->bindValue(1, $idSis, PDO::PARAM_INT);
            $sqlLista->bindValue(2, $idNivel, PDO::PARAM_INT);
            $sqlLista->bindValue(3, $idSis, PDO::PARAM_INT);
            $sqlLista->execute();
            $conn->commit();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'Vazio';
            };
        } else {
            return 'varFalse';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//listar categorias nivel de acesso Group By
function NivelAcessoCategoria()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT idnivel, titulo, categoria "
            . "FROM nivel GROUP BY categoria");
        //            $sqlLista->bindValue(1, $categoria, PDO::PARAM_INT);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function NivelNomeCategoria($categoria)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT idnivel, titulo, categoria "
            . "FROM nivel WHERE categoria = ? ");
        $sqlLista->bindValue(1, $categoria, PDO::PARAM_INT);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function NivelBtnSelecionado($idnivel, $idsis)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT idnivel "
            . "FROM nivelacesso "
            . "WHERE idnivel = ? AND idsis = ? ");
        $sqlLista->bindValue(1, $idnivel, PDO::PARAM_INT);
        $sqlLista->bindValue(2, $idsis, PDO::PARAM_INT);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return 'Existente';
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function verificarDoisCampo($tabela, $campo, $campoId, $campoId2, $campoParamentro, $campoParamentro2)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campo "
            . "FROM $tabela "
            . "WHERE $campoId = ? AND $campoId2=? ");
        $sqlLista->bindValue(1, $campoParamentro, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $campoParamentro2, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return 'Existente';
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function verificarCampo($tabela, $campo, $campoId, $campoParamentro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campo "
            . "FROM $tabela "
            . "WHERE $campoId = ? ");
        $sqlLista->bindValue(1, $campoParamentro, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return 'Existente';
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function verificarCampoTresParametros($tabela, $campo, $campoId, $campoId2, $campoId3, $campoParamentro, $campoParametro2, $campoParametro3)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campo "
            . "FROM $tabela "
            . "WHERE $campoId = ? AND $campoId2 = ? AND $campoId3 = ? ");
        $sqlLista->bindValue(1, $campoParamentro, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $campoParametro2, PDO::PARAM_STR);
        $sqlLista->bindValue(3, $campoParametro3, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return 'Existente';
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function verificarCampoQuatroParametros($tabela, $campo, $campoId, $campoId2, $campoId3, $campoId4, $campoParamentro, $campoParametro2, $campoParametro3, $campoParametro4)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campo "
            . "FROM $tabela "
            . "WHERE $campoId = ? AND $campoId2 = ? AND $campoId3 = ?  AND $campoId4 = ? ");
        $sqlLista->bindValue(1, $campoParamentro, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $campoParametro2, PDO::PARAM_STR);
        $sqlLista->bindValue(3, $campoParametro3, PDO::PARAM_STR);
        $sqlLista->bindValue(4, $campoParametro4, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return 'Existente';
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//alerta das mensagens
function mensagemAlerta($corTipoMensagem, $tituloMensagem, $mensagem)
{
    echo "<div class='row'>
        <div class='col-12 text-center'>
            <h2 class='text-muted'>Foram encontrado(s)&nbsp;0&nbsp;Registro(s)</h2>
        </div>
        <div class='col-lg-12'>
            <div class='ibox'>
                <div class='ibox-title'>
                    <div class='alert alert-$corTipoMensagem alert-dismissible fade show text-center' role='alert'>
                        <strong>$tituloMensagem</strong> $mensagem
                    </div>
                </div>
            </div>
        </div>
    </div>";
}

//Mensagem Botao
function mensagemBtn($corTipoMensagem, $corTextoBtn, $titleMensagem, $nomeBotao)
{
    echo "<button class='btn btn-sm btn-$corTipoMensagem text-$corTextoBtn' type='button' title='$nomeBotao $titleMensagem'><i class='mdi mdi-library-plus'></i>$nomeBotao</button>";
}

function mensagemBtnGrande($corTipoMensagem, $corTextoBtn, $titleMensagem, $nomeBotao)
{
    echo "<button class='btn btn-sm btn-$corTipoMensagem text-$corTextoBtn btn-block' type='button' title='$nomeBotao $titleMensagem'><i class='mdi mdi-library-plus'></i>$nomeBotao</button>";
}

//Listar REgistro Parametro
function listarRegistroParam($tabela, $campos, $campoComparacao, $campoParametro, $ativo, $campoOrdem, $ordemby = 'ASC')
{
    $conn = conectar();
    try {
        if (is_numeric($campoParametro)) {
            $conn->beginTransaction();
            $sqlLista = $conn->prepare("SELECT $campos "
                . "FROM $tabela "
                . "WHERE $campoComparacao = ? AND ativo = ? ORDER BY $campoOrdem $ordemby");
            $sqlLista->bindValue(1, $campoParametro, PDO::PARAM_INT);
            $sqlLista->bindValue(2, $ativo, PDO::PARAM_STR);
            $sqlLista->execute();
            $conn->commit();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'false';
            };
        } else {
            return 'false';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar REgistro Parametro maior ou menor
function listarRegistroParamMaiorMenor($tabela, $campos, $campoComparacao, $campoParametro, $ativo, $maiorMenor)
{
    $conn = conectar();
    try {
        if (is_numeric($campoParametro)) {
            $conn->beginTransaction();
            if ($maiorMenor == 'maior') {
                $maiorMenor = 'MAX';
            } else {
                $maiorMenor = 'MIN';
            }
            $sqlLista = $conn->prepare("SELECT $maiorMenor($campos) AS resultado "
                . "FROM $tabela "
                . "WHERE $campoComparacao = ? AND ativo = ? ");
            $sqlLista->bindValue(1, $campoParametro, PDO::PARAM_INT);
            $sqlLista->bindValue(2, $ativo, PDO::PARAM_STR);
            $sqlLista->execute();
            $conn->commit();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'false';
            };
        } else {
            return 'false';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar REgistro Parametro maior ou menor geral sem ser ativo ou inativo
function listarRegistroParamMaiorMenorNativo($tabela, $campos, $campoComparacao, $campoParametro, $maiorMenor)
{
    $conn = conectar();
    try {
        if (is_numeric($campoParametro)) {
            $conn->beginTransaction();
            if ($maiorMenor == 'maior') {
                $maiorMenor = 'MAX';
            } else {
                $maiorMenor = 'MIN';
            }
            $sqlLista = $conn->prepare("SELECT $maiorMenor($campos) AS resultado "
                . "FROM $tabela "
                . "WHERE $campoComparacao = ? ");
            $sqlLista->bindValue(1, $campoParametro, PDO::PARAM_INT);
            $sqlLista->execute();
            $conn->commit();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'false';
            };
        } else {
            return 'false';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar Todos registro Paginação
function listarRegPagi($tabela, $campos, $orderby, $inicio, $maximo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT $campos "
            . "FROM $tabela "
            . "ORDER BY $orderby "
            . "LIMIT $inicio , $maximo ");
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar Todos registro Paginação Com parametro
function listarRegPagiParametro($tabela, $campos, $inicio, $maximo, $campoCondicao, $campoParametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela WHERE $campoCondicao = ? "
            . "LIMIT $inicio , $maximo ");
        $sqlLista->bindValue(1, $campoParametro, PDO::PARAM_INT);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'false';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//contador de registro
function contadorRegistro($tabela, $campo, $condicao)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->query("SELECT COUNT(*) AS total_registros FROM $tabela WHERE $campo = $condicao");
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//contador de registro com paremetro
function contadorRegistroUnico($tabela, $campo, $condicao)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->query("SELECT COUNT(*) AS total_registros FROM $tabela WHERE $campo = $condicao");
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function contadorRegistroParametros($tabela, $campoCondicaoJuntos)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->query("SELECT COUNT(*) AS total_registros FROM $tabela WHERE $campoCondicaoJuntos");
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//contador de registro
function contadorRegistroTodos($tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->query("SELECT COUNT(*) AS total_registros FROM $tabela ");
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//contador de registro live
function contadorRegistroLive()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->query("SELECT COUNT(*) AS total_registros "
            . "FROM live l "
            . "INNER JOIN produto p ON p.idproduto = l.idproduto "
            . "WHERE (NOW() BETWEEN TIMESTAMP(l.apresentacao) AND TIMESTAMP(l.apresentacaofim)) ORDER BY l.ordem ");
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//contador de registro live
function contadorRegistroLiveId($idConfigLive)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM live l "
            . "INNER JOIN produto p ON p.idproduto = l.idproduto "
            . "WHERE l.idliveconfig=? ORDER BY l.ordem ");
        $sqlListaCount->bindValue(1, $idConfigLive, PDO::PARAM_INT);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//contador de registro CX reserva live
function contadorRegistroCXreservaLive($idAdmOperador)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros FROM caixareserva WHERE concluido = 'N' AND idsis = ? ");
        $sqlListaCount->bindValue(1, $idAdmOperador, PDO::PARAM_INT);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//contador relatorio geral para vendas e clientes========================================================
//-----------------compras canceladas- Condição= 5,6,7
//-----------------compras concluidas- Condição= 3,4
function contadorRelatorioVendasGeral($condicao)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM cliente c "
            . "INNER JOIN carrinho car ON car.idcliente = c.idcliente "
            . "INNER JOIN pagamento pag ON pag.idcarrinho = car.idcarrinho "
            . "WHERE pag.estatus IN ($condicao) ");
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function contadorRelatorioVendasGeralProduto($condicao, $dataInicio, $dataFim)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM cliente c "
            . "INNER JOIN carrinho car ON car.idcliente = c.idcliente "
            . "INNER JOIN carrinhoproduto cp ON cp.idcarrinho = car.idcarrinho "
            . "INNER JOIN produto p ON p.idproduto = cp.idproduto "
            . "INNER JOIN pagamento pag ON pag.idcarrinho = car.idcarrinho "
            . "WHERE (pag.cadastro BETWEEN ? AND ?) AND pag.estatus IN ($condicao) ");
        $sqlListaCount->bindValue(1, $dataInicio, PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, $dataFim, PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//geral com data
function contadorRelatorioVendasGeralData($condicao, $dataInicio, $dataFim)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM cliente c "
            . "INNER JOIN carrinho car ON car.idcliente = c.idcliente "
            . "INNER JOIN pagamento pag ON pag.idcarrinho = car.idcarrinho "
            . "WHERE (pag.cadastro BETWEEN ? AND ?) AND pag.estatus IN ($condicao) ");
        $sqlListaCount->bindValue(1, $dataInicio, PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, $dataFim, PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

//=====================================================================================================================
//contador de registro Localizar
function contadorRegistroLocalizar($tabela, $campoCondicao, $campoCondicao2, $inputLocalizar, $iniciopag, $maximopag)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) as total_registros FROM $tabela WHERE $campoCondicao = ? OR $campoCondicao2 like ? LIMIT $iniciopag, $maximopag");
        $sqlListaCount->bindValue(1, $inputLocalizar, PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$inputLocalizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function popularGeral($tabela, $campos, $idCampo, $idparametro, $campoAtivo, $ativo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idCampo = ? AND $campoAtivo = ? ");
        $sqlLista->bindValue(1, "$idparametro", PDO::PARAM_INT);
        $sqlLista->bindValue(2, "$ativo", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarAdministrador($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT s.* "
            . "FROM sis s "
            . "WHERE 1=1 AND s.idsis like ? OR s.nome like ? OR s.cpf like ? OR s.celular like ? OR s.email like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(5, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarEstrutura($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT e.*, te.idtipoestrutura, te.tipoestrutura "

            . "FROM estrutura e INNER JOIN tipoestrutura te ON te.idtipoestrutura = e.tipo "
            . "WHERE 1=1 AND e.idestrutura like ? OR e.tipo like ? OR e.numero like ? OR e.descricao like ? OR e.lotacao like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(5, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarMarca($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT * "

            . "FROM marca "
            . "WHERE 1=1 AND idmarca like ? OR marca like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarProdutoConfeitaria($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT p.idproduto, p.idmarca, p.nome, 
        p.descricao, p.peso, p.cadastro, p.ativo, m.marca "
            . "FROM produto p "
            . "INNER JOIN marca m "
            . "ON m.idmarca = p.idmarca AND p.ativo = 'A' "
            . "WHERE 1=1 AND p.nome like ? OR p.descricao like ? OR p.peso like ? OR m.marca like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarReceitaConfeitaria($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT idreceita, nome, cadastro, alteracao, ativo "
            . "FROM receita "
            . "WHERE 1=1 AND idreceita like ? OR nome like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarTipoEstrutura($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT * "
            . "FROM tipoestrutura "
            . "WHERE 1=1 AND idtipoestrutura like ? OR tipoestrutura like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarDepartamento($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT d.* "
            . "FROM departamento d "
            . "WHERE 1=1 AND d.titulo like ? OR d.subtitulo like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarColaborador($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT c.* "
            . "FROM colaborador c "
            . "WHERE 1=1 AND c.idcolaborador like ? OR c.nome like ? OR c.funcao like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarFoto($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT f.* "
            . "FROM foto f "
            . "WHERE 1=1 AND f.idfoto like ? OR f.legenda like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarPergunta($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT p.* "
            . "FROM pergunta p "
            . "WHERE 1=1 AND p.idpergunta like ? OR p.pergunta like ? OR p.resposta like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarEmail($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT e.* "
            . "FROM emaildepartamento e "
            . "WHERE 1=1 AND e.idemaildepartamento like ? OR e.departamento like ? OR e.email like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarDepoimento($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT d.* "
            . "FROM depoimento d "
            . "WHERE 1=1 AND d.iddepoimento like ? OR d.nome like ? OR d.funcao like ? OR d.texto like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarBanner($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT b.* "
            . "FROM banner b "
            . "WHERE 1=1 AND b.idbanner like ? OR b.titulo like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarPeriodo($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT p.* "
            . "FROM periodo p "
            . "WHERE 1=1 AND p.idperiodo like ? OR p.periodo like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarRevista($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT r.*, p.periodo "
            . "FROM revista r INNER JOIN periodo p ON p.idperiodo = r.idperiodo "
            . "WHERE 1=1 AND r.idrevista like ? OR r.titulo like ? OR r.descricao like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarArtigo($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT a.idartigo, a.idrevista, a.arquivo, a.titulo, a.descricao, a.cadastro, a.alteracao, a.ativo, r.idrevista, r.img, r.titulo as titulorevista, p.periodo "
            . "FROM artigo a INNER JOIN revista r ON a.idrevista = r.idrevista INNER JOIN periodo p ON p.idperiodo = r.idperiodo "
            . "WHERE 1=1 AND a.idartigo like ? OR a.titulo like ? OR a.descricao like ? OR p.periodo like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//contador de registro
function buscarAdministradorCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM sis s "
            . "WHERE 1=1 AND s.idsis like ? OR s.nome like ? OR s.cpf like ? OR s.celular like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarEstruturaCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM estrutura e "
            . "WHERE 1=1 AND e.idestrutura like ? OR e.tipo like ? OR e.numero like ? OR e.descricao like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarMarcaCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM marca "
            . "WHERE 1=1 AND idmarca like ? OR marca like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarCountTabela2Campo($tabela, $idTabela, $nomeCampoBuscar, $localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM $tabela "
            . "WHERE 1=1 AND $idTabela like ? OR $nomeCampoBuscar like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarCountTabela4Campo($tabela, $idTabela1, $nomeCampoBuscar2, $nomeCampoBuscar3, $nomeCampoBuscar4, $localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM $tabela "
            . "WHERE 1=1 AND $idTabela1 like ? OR $nomeCampoBuscar2 like ? OR $nomeCampoBuscar3 like ?  OR $nomeCampoBuscar4 like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarGeral2Campos($tabela, $idTabela, $nomeCampoBuscar, $localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT * "

            . "FROM $tabela "
            . "WHERE 1=1 AND $idTabela like ? OR $nomeCampoBuscar like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarGeral4Campos($tabela, $campos, $idTabela1, $nomeCampoBuscar2, $nomeCampoBuscar3, $nomeCampoBuscar4, $localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE 1=1 AND $idTabela1 like ? OR $nomeCampoBuscar2 like ? OR $nomeCampoBuscar3 like ? OR $nomeCampoBuscar4 like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarProdutoCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM produto "
            . "WHERE 1=1 AND idproduto like ? OR nome like ?  OR peso like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarReceitaCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM receita "
            . "WHERE 1=1 AND idreceita like ? OR nome like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarTipoEstruturaCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM tipoestrutura e "
            . "WHERE 1=1 AND e.idtipoestrutura like ? OR e.tipoestrutura like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarColaboradorCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM colaborador c "
            . "WHERE 1=1 AND c.idcolaborador like ? OR c.nome like ? OR c.funcao like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarFotoCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM foto f "
            . "WHERE 1=1 AND f.idfoto like ? OR f.legenda like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarDepoimentoCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM depoimento d "
            . "WHERE 1=1 AND d.iddepoimento like ? OR d.nome like ? OR d.funcao like ? OR d.texto like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(4, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarPerguntaCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM pergunta p "
            . "WHERE 1=1 AND p.idpergunta like ? OR p.pergunta like ? OR p.resposta like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarEmailCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM emaildepartamento e "
            . "WHERE 1=1 AND e.idemaildepartamento like ? OR e.departamento like ? OR e.email like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarPeriodoCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM periodo p "
            . "WHERE 1=1 AND p.idperiodo like ? OR p.periodo like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarRevsitaCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM revista r "
            . "WHERE 1=1 AND r.idrevista like ? OR r.titulo like ? OR r.descricao like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarArtigoCount($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM artigo a "
            . "WHERE 1=1 AND a.idartigo like ? OR a.titulo like ? OR a.descricao like ? ");
        $sqlListaCount->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $rsContador = null;
    $conn = null;
}

function buscarCliente($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT c.* "
            . "FROM cliente c "
            . "WHERE 1=1 AND c.nome like ? OR c.cpf like ? OR c.celular like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function buscarProduto($localizar)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT p.idproduto, p.idmenusubcat, p.idmenucat, p.foto, p.nomeproduto, 
        p.codigo, p.destaque, p.datainicio, p.datafim, p.cadastro, p.ativo, msc.subcategoria, mc.categoria, m.menu, 
        e.idestoque, e.quantidade "
            . "FROM produto p "
            . "LEFT JOIN estoque e "
            . "ON e.idproduto = p.idproduto AND e.ativo = 'A' "
            . "INNER JOIN menusubcat msc "
            . "ON msc.idmenusubcat = p.idmenusubcat "
            . "INNER JOIN menucat mc "
            . "ON mc.idmenucat = p.idmenucat "
            . "INNER JOIN menu m "
            . "ON m.idmenu = mc.idmenu "
            . "WHERE 1=1 AND p.nomeproduto like ? OR p.codigo like ? OR p.destaque like ? ");
        $sqlLista->bindValue(1, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(2, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->bindValue(3, "%$localizar%", PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//contador de registro vendas Estoque
function contadorRegistroVendasEstoque()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaCountClientes = $conn->query("SELECT COUNT(*) AS total_registros "
            . "FROM estoque e "
            . "INNER JOIN baixaestoque ba ON ba.idestoque = e.idestoque "
            . "INNER JOIN produto p ON e.idproduto = p.idproduto "
            . "WHERE ba.ativo = 'A' ");
        $sqlListaCountClientes->execute();
        $conn->commit();
        if ($sqlListaCountClientes->rowCount() > 0) {
            $rsContador = $sqlListaCountClientes->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//venda estoque por data
//contador de registro vendas Estoque
function contadorRegistroVendasEstoqueData($dataInicio, $dataFim)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaCountClientes = $conn->prepare("SELECT COUNT(*) AS total_registros "
            . "FROM estoque e "
            . "INNER JOIN baixaestoque ba ON ba.idestoque = e.idestoque "
            . "INNER JOIN produto p ON e.idproduto = p.idproduto "
            . "WHERE (ba.cadastro BETWEEN ? AND ?) AND ba.ativo = 'A' ");
        $sqlListaCountClientes->bindValue(1, $dataInicio, PDO::PARAM_STR);
        $sqlListaCountClientes->bindValue(2, $dataFim, PDO::PARAM_STR);
        $sqlListaCountClientes->execute();
        $conn->commit();
        if ($sqlListaCountClientes->rowCount() > 0) {
            $rsContador = $sqlListaCountClientes->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["total_registros"];
            return $total;
        } else {
            return false;
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//contador de registro vendas Estoque
function contadorSimples($campoSomar, $tabela, $campoCondiacao, $parametroId, $campoAtivo, $ativo)
{;
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlListaCount = $conn->prepare("SELECT sum($campoSomar) as totalSaida FROM $tabela WHERE $campoCondiacao = ? AND $campoAtivo = ? ");
        $sqlListaCount->bindValue(1, $parametroId, PDO::PARAM_INT);
        $sqlListaCount->bindValue(2, $ativo, PDO::PARAM_STR);
        $sqlListaCount->execute();
        $conn->commit();
        if ($sqlListaCount->rowCount() > 0) {
            $rsContador = $sqlListaCount->fetch(PDO::FETCH_ASSOC);
            $total = $rsContador["totalSaida"];
            return $total;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

function contadorRegistroVendasEstoqueUnificado()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaCountClientes = $conn->query("SELECT p.idproduto, p.nomeproduto, e.quantidade, SUM(ba.quantidade) as QTDVendida, e.quantidade - SUM(ba.quantidade) as NovoEstoque, format((SUM(ba.quantidade * e.valorvenda)),2,'de_DE') AS VALORtotal "
            . "FROM estoque e "
            . "INNER JOIN baixaestoque ba ON ba.idestoque = e.idestoque "
            . "INNER JOIN produto p ON e.idproduto = p.idproduto "
            . "WHERE ba.ativo = 'A' "
            . "GROUP BY p.idproduto ");
        $sqlListaCountClientes->execute();
        $conn->commit();
        if ($sqlListaCountClientes->rowCount() > 0) {
            return $sqlListaCountClientes->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

function produtoEstoqueConfeitaria()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaCountClientes = $conn->query("SELECT p.idproduto, e.idestoque, p.idmarca, ma.marca, p.nome, p.peso as pesoproduto, e.quantidade, e.vencimento, p.peso * e.quantidade as pesoInicial, NVL(SUM(be.peso), 0) as pesobaixado, NVL(((p.peso * e.quantidade) - SUM(be.peso)), p.peso * e.quantidade) as totalEstoque "
            . "FROM produto p "
            . "INNER JOIN marca ma ON ma.idmarca = p.idmarca "
            . "INNER JOIN estoque e ON e.idproduto = p.idproduto "
            . "LEFT JOIN baixaestoque be ON be.idestoque = e.idestoque "
            . "WHERE e.ativo = 'A' "
            . "GROUP BY idestoque "
            . "ORDER BY p.nome ASC ");
        $sqlListaCountClientes->execute();
        $conn->commit();
        if ($sqlListaCountClientes->rowCount() > 0) {
            return $sqlListaCountClientes->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

function produtoEstoqueConfeitariaGroupBy()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaEstoqueConf = $conn->query("SELECT p.idproduto, e.idestoque, p.idmarca, ma.marca, p.nome, p.peso as pesoproduto, e.quantidade, e.vencimento, p.peso * e.quantidade as pesoInicial, NVL(SUM(be.peso), 0) as pesobaixado, NVL(((p.peso * SUM(e.quantidade)) - SUM(be.peso)), p.peso * SUM(e.quantidade)) as totalEstoque "
            . "FROM produto p "
            . "INNER JOIN marca ma ON ma.idmarca = p.idmarca "
            . "INNER JOIN estoque e ON e.idproduto = p.idproduto "
            . "LEFT JOIN baixaestoque be ON be.idestoque = e.idestoque "
            . "WHERE e.ativo = 'A' "
            . "GROUP BY idproduto "
            . "ORDER BY p.nome ASC ");
        $sqlListaEstoqueConf->execute();
        $conn->commit();
        if ($sqlListaEstoqueConf->rowCount() > 0) {
            return $sqlListaEstoqueConf->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

function listarProdReceitaGroupBy($idreceita, $ativo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaProdConf = $conn->prepare("SELECT rp.idreceitaproduto, rp.idreceita, rp.peso as pesoReceitaProduto, p.idproduto, p.cadastro, p.alteracao, p.ativo, e.idestoque, e.vencimento, p.idmarca, ma.marca, p.nome, p.peso as pesoprodutoEstoque, SUM(e.quantidade) as totalQtdProd, p.peso * SUM(e.quantidade) as pesoInicial, NVL(SUM(be.peso), 0) as pesobaixado, NVL(((p.peso * SUM(e.quantidade)) - SUM(be.peso)), p.peso * SUM(e.quantidade)) as totalEstoque, e.vencimento "
            . "FROM produto p "
            . "INNER JOIN receitaproduto rp ON p.idproduto = rp.idproduto "
            . "INNER JOIN marca ma ON ma.idmarca = p.idmarca "
            . "INNER JOIN estoque e ON e.idproduto = p.idproduto "
            . "LEFT JOIN baixaestoque be ON be.idestoque = e.idestoque "
            . "WHERE e.ativo = ? AND rp.idreceita = ? "
            . "GROUP BY e.idproduto "
            . "ORDER BY rp.idreceitaproduto DESC ");
        $sqlListaProdConf->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlListaProdConf->bindValue(2, $idreceita, PDO::PARAM_INT);
        $sqlListaProdConf->execute();
        $conn->commit();
        if ($sqlListaProdConf->rowCount() > 0) {
            return $sqlListaProdConf->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

function listarProdAddBaixaEstoque($idreceita, $ativo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlListaProdConf = $conn->prepare("SELECT est.idestoque, y.idreceita, y.peso, est.quantidade, est.idproduto "
            . "FROM estoque est "
            . "INNER JOIN(SELECT res.idestoque, rp.idreceita, rp.peso, rp.idproduto "
            . "FROM receitaproduto rp "
            . "INNER JOIN (SELECT idproduto, MAX(idestoque) as idestoque "
            . "FROM estoque "
            . "WHERE ativo = ? "
            . "GROUP BY idproduto)res ON res.idproduto = rp.idproduto "
            . "WHERE rp.idreceita = ? "
            . "ORDER BY rp.idproduto DESC) y ON y.idestoque = est.idestoque ");
        $sqlListaProdConf->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlListaProdConf->bindValue(2, $idreceita, PDO::PARAM_INT);
        $sqlListaProdConf->execute();
        $conn->commit();
        if ($sqlListaProdConf->rowCount() > 0) {
            return $sqlListaProdConf->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//Listar Quantidade REgistro Unico, usando rowCount PDO
function listarContadorRegistroUnicoPDO($tabela, $campoConsulta, $idRegistroParametro, $ativo = 'A')
{
    $conn = conectar();
    try {
        if (is_numeric($idRegistroParametro)) {
            $conn->beginTransaction();
            $sqlLista = $conn->prepare("SELECT $campoConsulta "
                . "FROM $tabela "
                . "WHERE $campoConsulta = ? AND ativo = ? ");
            $sqlLista->bindValue(1, $idRegistroParametro, PDO::PARAM_INT);
            $sqlLista->bindValue(2, $ativo, PDO::PARAM_STR);
            $sqlLista->execute();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->rowCount();
            } else {
                return 0;
            };
        } else {
            return 'Variável Não é aceita!';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar Registro Unico Unico Ativo
function listarRegistroUnico($tabela, $campos, $idcampo, $idparametro, $ativo, $campoOrdenar, $orderby = 'ASC')
{
    $conn = conectar();
    try {
        if (is_numeric($idparametro)) {
            $conn->beginTransaction();
            $sqlLista = $conn->prepare("SELECT $campos "
                . "FROM $tabela "
                . "WHERE $idcampo = ? AND ativo = '$ativo' ORDER BY $campoOrdenar $orderby ");
            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
            $sqlLista->execute();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'Vazio';
            };
        } else {
            return 'Variável Não é aceita!';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarCarrinho($id)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT tbcarrinho.idcarrinho, tbcarrinho.qtdd, tbartesvend.idartesvend, tbartesvend.imgarte, tbartesvend.titulo, tbartesvend.descricao, tbartesvend.valor, tbusuario.nome
        FROM tbcarrinho
        INNER JOIN tbartesvend ON tbartesvend.idartesvend = tbcarrinho.idartesvend
        INNER JOIN tbvendedor ON tbartesvend.idvendedor = tbvendedor.idvendedor
        INNER JOIN tbusuario ON tbvendedor.idusuario = tbusuario.idusuario
        WHERE tbcarrinho.idusuario = ?
        ORDER BY tbcarrinho.idcarrinho ASC");
        $sqlLista->bindValue(1, $id, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarCarrinhoCount($idArte, $idUser)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT COUNT(idcarrinho) AS qntdd FROM tbcarrinho WHERE idartesvend = ? AND idusuario = ?");
        $sqlLista->bindValue(1, $idArte, PDO::PARAM_INT);
        $sqlLista->bindValue(2, $idUser, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroDoisParametro($tabela, $campos, $idcampo, $idparametro, $idCampo2, $idparametro2, $ativo)
{
    $conn = conectar();
    try {
        if (is_numeric($idparametro)) {
            $conn->beginTransaction();
            $sqlLista = $conn->prepare("SELECT $campos "
                . "FROM $tabela "
                . "WHERE $idcampo = ? AND $idCampo2 = ? AND ativo = '$ativo' ");
            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
            $sqlLista->bindValue(2, $idparametro2, PDO::PARAM_STR);
            $sqlLista->execute();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'Vazio';
            };
        } else {
            return 'Variável Não é aceita!';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroParametroIN($tabela, $campos, $idcampo, $idparametro, $idCampo2, $idparametro2, $ativo)
{
    $conn = conectar();
    try {
        if (is_numeric($idparametro)) {
            $conn->beginTransaction();
            $sqlLista = $conn->prepare("SELECT $campos "
                . "FROM $tabela "
                . "WHERE $idcampo = ? AND $idCampo2 IN($idparametro2) AND ativo = '$ativo' ");
            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
            $sqlLista->execute();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'Vazio';
            };
        } else {
            return 'Variável Não é aceita!';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroParametroINPaginacao($tabela, $campos, $idcampo, $idparametro, $idCampo2, $idparametro2, $ativo, $inicio, $maximo)
{
    $conn = conectar();
    try {
        if (is_numeric($idparametro)) {
            $conn->beginTransaction();
            $sqlLista = $conn->prepare("SELECT $campos "
                . "FROM $tabela "
                . "WHERE $idcampo = ? AND $idCampo2 IN($idparametro2) AND ativo = '$ativo' LIMIT $inicio, $maximo ");
            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
            $sqlLista->execute();
            if ($sqlLista->rowCount() > 0) {
                return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 'Vazio';
            };
        } else {
            return 'Variável Não é aceita!';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroINPaginacao($tabela, $campos, $idcampo, $idparametro, $inicio, $maximo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo IN($idparametro) LIMIT $inicio, $maximo ");
        //            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroINPaginacaoAtivo($tabela, $campos, $idcampo, $idparametro, $ativo, $inicio, $maximo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo IN($idparametro) AND ativo = '$ativo' LIMIT $inicio, $maximo ");
        //            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroIN($tabela, $campos, $idcampo, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo IN($idparametro)");
        //            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegistroINAtivo($tabela, $campos, $idcampo, $idparametro, $ativo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo IN($idparametro) AND ativo = '$ativo' ");
        //            $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar Registro Unico não importa se é ativo ou desativado
function listarRegistroU($tabela, $campos, $idcampo, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}
function listarRegistroUAssoc($tabela, $campos, $idcampo, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            //            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
            return $sqlLista->fetch(PDO::FETCH_ASSOC);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}
function listarRegistroUOrdem($tabela, $campos, $idcampo, $idparametro, $campoOrdem, $orderBy = 'ASC')
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ORDER BY $campoOrdem $orderBy");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarReservaEstrutura($idEstrutura, $dataInicio, $dataFim)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT idcalendario, idsis, idestrutura, obs, inicio, fim, cadastro, alteracao, ativo "
            . "FROM calendario "
            . "WHERE idestrutura = ? "
            . "AND DATE(?) BETWEEN DATE(inicio) AND DATE(fim) "
            . "AND (TIME(?) BETWEEN TIME(inicio) AND TIME(fim) "
            . "OR TIME(?) BETWEEN TIME(inicio) AND TIME(fim)) ");
        $sqlLista->bindValue(1, $idEstrutura, PDO::PARAM_INT);
        $sqlLista->bindValue(2, $dataInicio, PDO::PARAM_STR);
        $sqlLista->bindValue(3, $dataInicio, PDO::PARAM_STR);
        $sqlLista->bindValue(4, $dataFim, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarRegUnAssoc($tabela, $campos, $idcampo, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetch(PDO::FETCH_ASSOC);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Listar todos Registro Unico não importa se é ativo ou desativado
function listarTodosRegistroU($tabela, $campos, $idcampo, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarTodosRegistroUorder($tabela, $campos, $idcampo, $idparametro, $OrderBy)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campos "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ORDER BY $OrderBy");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarGeral($campos, $tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT $campos "
            . "FROM $tabela ");
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarGeralCount($campos, $tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT $campos FROM $tabela");
        $sqlLista->execute();

        $resultados = $sqlLista->fetchAll(PDO::FETCH_OBJ);
        $quantidade = $sqlLista->rowCount();

        return [
            'dados' => $resultados,
            'quantidade' => $quantidade,
        ];
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ['error' => $e->getMessage()];
        $conn->rollback();
    } finally {
        $conn = null;
    }
}


function listarGeralInnerJoin($campos, $tabelaPrincipal, $tabelaJoin, $condicaoJoin)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlLista = $conn->query("SELECT $campos FROM $tabelaPrincipal
                                 INNER JOIN $tabelaJoin ON $condicaoJoin");
        $sqlLista->execute();

        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        }
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return $e->getMessage();
        $conn->rollback();
    } finally {
        $conn = null;
    }
}


// listar eventos
function listarEventos($campos, $tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT $campos "
            . "FROM $tabela ");
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarTodosPizza($campo, $tabela)
{
    $conn = conectar();
    $queryListar = $conn->prepare("SELECT $campo FROM $tabela ");
    //    $queryListar->bindValue(1, $campo, PDO::PARAM_STR);
    //    $queryListar->bindValue(2, $tabela, PDO::PARAM_STR);
    $queryListar->execute();
    if ($queryListar->rowCount() > 0) {
        return $queryListar->fetchAll(PDO::FETCH_OBJ);
    } else {
        return 'Vazio';
    }
}

function insertTresPizza($tabela, $campos, $value1, $value2, $value3)
{
    $conn = conectar();
    $queryInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES (?,?,?) ");
    $queryInsert->bindValue(1, $value1, PDO::PARAM_STR);
    $queryInsert->bindValue(2, $value2, PDO::PARAM_STR);
    $queryInsert->bindValue(3, $value3, PDO::PARAM_STR);
    $queryInsert->execute();
    $idGravado = $conn->lastInsertId();
    if ($queryInsert->rowCount() > 0) {
        return $idGravado;
    } else {
        return 'Vazio';
    }
}


//Listar CEP
function listarCepFrete($idcliente, $entrega = 'S')
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT cep "
            . "FROM enderecos "
            . "WHERE entrega = ? AND idcliente = ? ORDER BY idcliente DESC");
        $sqlLista->bindValue(1, $entrega, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $idcliente, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//listar todos dados da tabela
function listarTodosTabela($tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT * FROM $tabela");
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//listar todos dados da tabela
function listarTodosTabelaOrdem($tabela, $Ordem = 'ASC')
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT * FROM $tabela ORDER BY $Ordem ");
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//listar todos dados da tabela diferente do ID
function listarTodosTabelaDiferenteID($campo, $id, $tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT * FROM $tabela WHERE $campo <> ? ");
        $sqlLista->bindValue(1, $id, PDO::PARAM_INT);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function listarTodosTabelaDiferenteIDstring($campo, $id, $tabela)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT * FROM $tabela WHERE $campo <> ? ");
        $sqlLista->bindValue(1, $id, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//listar todos dados da tabela
function listarTodos($campo, $tabela, $campoAtivo, $campoOrdem, $ativo)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->prepare("SELECT $campo FROM $tabela WHERE $campoAtivo = ? ORDER BY $campoOrdem ");
        $sqlLista->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//QUANTIDADE DE PERGUNTAS DE CADA ASPECTO
function qtdPerguntaApectoRespondida()
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLista = $conn->query("SELECT a.*, count(q.idaspecto) as totalpergunta FROM aspecto a LEFT JOIN questao q ON q.idaspecto = a.idaspecto AND q.ativo = 'A' WHERE a.ativo = 'A' GROUP BY q.idaspecto");
        $sqlLista->execute();
        $conn->commit();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return 'Vazio';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//-------------------------------------------INSERT---------------------------------------------------------------------
//insert auditoria
function insertAuditoria($idsis, $responsavel, $acao, $idafetado, $nomeafetado, $tipoacao, $tabela, $cadastro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO auditoria(idpessoa, responsavel, acao, idafetado, nomeafetado, tipoacao, tabela, cadastro)VALUES(?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $idsis, PDO::PARAM_INT);
        $sqInsert->bindValue(2, $responsavel, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $acao, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $idafetado, PDO::PARAM_INT);
        $sqInsert->bindValue(5, $nomeafetado, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $tipoacao, PDO::PARAM_INT);
        $sqInsert->bindValue(7, $tabela, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $cadastro, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Auditoria Gravada com Sucesso';
        } else {
            return 'Auditoria Não Gravada';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDois($tabela, $campos, $valeu1, $valeu2)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?)");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDoisNum($tabela, $campos, $valeu1, $valeu2)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?, NOW())");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_INT);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_INT);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDoisId($tabela, $campos, $valeu1, $valeu2)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?)");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertTres($tabela, $campos, $valeu1, $valeu2, $valeu3)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?)");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insert3Cad($tabela, $campos, $valeu1, $valeu2, $valeu3)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?, NOW())");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertTresId($tabela, $campos, $valeu1, $valeu2, $valeu3)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?)");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertQuatro($tabela, $campos, $valeu1, $valeu2, $valeu3, $valeu4)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?)");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertQuatroId($tabela, $campos, $value1, $value2, $value3, $value4)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);

        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertCinco($tabela, $campos, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?)");
        $sqInsert->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertCincoId($tabela, $campos, $value1, $value2, $value3, $value4, $value5)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);

        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertSeis($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}


function insertSete($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertOito($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertNove($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}



function insertQuinze($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13, $value14, $value15)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->bindValue(14, $value14, PDO::PARAM_STR);
        $sqInsert->bindValue(15, $value15, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDezesseis($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13, $value14, $value15, $value16)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->bindValue(14, $value14, PDO::PARAM_STR);
        $sqInsert->bindValue(15, $value15, PDO::PARAM_STR);
        $sqInsert->bindValue(16, $value16, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDezesseisId($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13, $value14, $value15, $value16)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->bindValue(14, $value14, PDO::PARAM_STR);
        $sqInsert->bindValue(15, $value15, PDO::PARAM_STR);
        $sqInsert->bindValue(16, $value16, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDezessete($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13, $value14, $value15, $value16, $value17)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->bindValue(14, $value14, PDO::PARAM_STR);
        $sqInsert->bindValue(15, $value15, PDO::PARAM_STR);
        $sqInsert->bindValue(16, $value16, PDO::PARAM_STR);
        $sqInsert->bindValue(17, $value17, PDO::PARAM_STR);
        $sqInsert->execute();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return 'Gravado';
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDezesseteId($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13, $value14, $value15, $value16, $value17)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->bindValue(14, $value14, PDO::PARAM_STR);
        $sqInsert->bindValue(15, $value15, PDO::PARAM_STR);
        $sqInsert->bindValue(16, $value16, PDO::PARAM_STR);
        $sqInsert->bindValue(17, $value17, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDezoitoId($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13, $value14, $value15, $value16, $value17, $value18)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->bindValue(14, $value14, PDO::PARAM_STR);
        $sqInsert->bindValue(15, $value15, PDO::PARAM_STR);
        $sqInsert->bindValue(16, $value16, PDO::PARAM_STR);
        $sqInsert->bindValue(17, $value17, PDO::PARAM_STR);
        $sqInsert->bindValue(18, $value18, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertDozeId($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function insertTrezeId($tabela, $campos, $value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10, $value11, $value12, $value13)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqInsert = $conn->prepare("INSERT INTO $tabela($campos)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $sqInsert->bindValue(1, $value1, PDO::PARAM_STR);
        $sqInsert->bindValue(2, $value2, PDO::PARAM_STR);
        $sqInsert->bindValue(3, $value3, PDO::PARAM_STR);
        $sqInsert->bindValue(4, $value4, PDO::PARAM_STR);
        $sqInsert->bindValue(5, $value5, PDO::PARAM_STR);
        $sqInsert->bindValue(6, $value6, PDO::PARAM_STR);
        $sqInsert->bindValue(7, $value7, PDO::PARAM_STR);
        $sqInsert->bindValue(8, $value8, PDO::PARAM_STR);
        $sqInsert->bindValue(9, $value9, PDO::PARAM_STR);
        $sqInsert->bindValue(10, $value10, PDO::PARAM_STR);
        $sqInsert->bindValue(11, $value11, PDO::PARAM_STR);
        $sqInsert->bindValue(12, $value12, PDO::PARAM_STR);
        $sqInsert->bindValue(13, $value13, PDO::PARAM_STR);
        $sqInsert->execute();
        $idInsertRetorno = $conn->lastInsertId();
        $conn->commit();
        if ($sqInsert->rowCount() > 0) {
            return $idInsertRetorno;
        } else {
            return 'nGravado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//-------------------------------------------UPDATE---------------------------------------------------------------------
//update Ativar ou desativar
function updateAtivar($tabela, $idcampo, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqlLista = $conn->prepare("SELECT ativo "
            . "FROM $tabela "
            . "WHERE $idcampo = ? ");
        $sqlLista->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            $retorno = $sqlLista->fetchAll(PDO::FETCH_OBJ);
            foreach ($retorno as $dados) {
                $situacao = $dados->ativo;
            }
        } else {
            $situacao = 0;
        };
        if ($situacao == 'A') {
            $situacao = 'D';
            $ativadoDesativado = 'Desativado';
        } else {
            $situacao = 'A';
            $ativadoDesativado = 'Ativado';
        }
        $sqUpdate = $conn->prepare("UPDATE $tabela SET ativo=? WHERE $idcampo = ?");
        $sqUpdate->bindValue(1, $situacao, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $idparametro, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return $ativadoDesativado;
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//update Foto
function updateFoto($tabela, $campoAlterado, $img, $campoIdReferencia, $campoIdParametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campoAlterado=? WHERE $campoIdReferencia = ? ");
        $sqUpdate->bindValue(1, $img, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $campoIdParametro, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function updateInt($tabela, $campoAlt, $valorAlt, $campoRef, $valorRef)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campoAlt = ? WHERE $campoRef = ? ");
        $sqUpdate->bindValue(1, $valorAlt, PDO::PARAM_INT);
        $sqUpdate->bindValue(2, $valorRef, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//UPdate ADm
function upAdmComSenha($idsis, $nome, $cpf, $nascimento, $email, $senha, $celular, $rua, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE sis SET nome=?, cpf=?, nascimento=?, email=?, senha=?, celular=?, rua=?, numero=?, complemento=?, bairro=?, cidade=?, estado=?, pais=?, cep=? WHERE idsis = ?");
        $sqUpdate->bindValue(1, $nome, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $cpf, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $nascimento, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $email, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $senha, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $celular, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $rua, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $numero, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $complemento, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $bairro, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $cidade, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $estado, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $pais, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $cep, PDO::PARAM_STR);
        $sqUpdate->bindValue(15, $idsis, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado com Sucesso';
        } else {
            return 'Nao Cadastrado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//UPdate Sem Senha ADm
function upAdmSemSenha($idsis, $nome, $cpf, $nascimento, $email, $celular, $rua, $numero, $complemento, $bairro, $cidade, $estado, $pais, $cep)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE sis SET nome=?, cpf=?, nascimento=?, email=?, celular=?, rua=?, numero=?, complemento=?, bairro=?, cidade=?, estado=?, pais=?, cep=? WHERE idsis = ?");
        $sqUpdate->bindValue(1, $nome, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $cpf, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $nascimento, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $email, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $celular, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $rua, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $numero, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $complemento, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $bairro, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $cidade, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $estado, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $pais, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $cep, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $idsis, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado com Sucesso';
        } else {
            return 'Nao Cadastrado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//UPdate cliente area ADm com senha
function upClienteAdm($nome, $nascimento, $gernero, $cpf, $celular, $email, $senha, $idcliente)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE cliente SET nome=?, nascimento=?, gernero = ?, cpf=?, celular=?, email=?, senha=? WHERE idcliente = ?");
        $sqUpdate->bindValue(1, $nome, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $nascimento, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $gernero, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $cpf, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $celular, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $email, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $senha, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $idcliente, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado com Sucesso';
        } else {
            return 'Nao Cadastrado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//UPdate cliente area ADm SEM a senha
function upClienteAdmSemSenha($nome, $nascimento, $gernero, $cpf, $celular, $email, $idcliente)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE cliente SET nome=?, nascimento=?, gernero = ?, cpf=?, celular=?, email=? WHERE idcliente = ?");
        $sqUpdate->bindValue(1, $nome, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $nascimento, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $gernero, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $cpf, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $celular, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $email, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $idcliente, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado com Sucesso';
        } else {
            return 'Nao Cadastrado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upUm($tabela, $campo1, $campoId, $valeu1, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}



function upDois($tabela, $campo1, $campo2, $campoId, $valeu1, $valeu2, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upTres($tabela, $campo1, $campo2, $campo3, $campoId, $valeu1, $valeu2, $valeu3, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upQuatro($tabela, $campo1, $campo2, $campo3, $campo4, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upCinco($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upSeis($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upSete($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upNove($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upDez($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upOnze($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upDoze($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campo12, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeu12, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ?, $campo12 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeu12, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upQuatorze($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campo12, $campo13, $campo14, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeu12, $valeu13, $valeu14, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ?, $campo12 = ?, $campo13 = ?, $campo14 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeu12, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $valeu13, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $valeu14, PDO::PARAM_STR);
        $sqUpdate->bindValue(15, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upQuinze($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campo12, $campo13, $campo14, $campo15, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeu12, $valeu13, $valeu14, $valeu15, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ?, $campo12 = ?, $campo13 = ?, $campo14 = ?, $campo15 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeu12, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $valeu13, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $valeu14, PDO::PARAM_STR);
        $sqUpdate->bindValue(15, $valeu15, PDO::PARAM_STR);
        $sqUpdate->bindValue(16, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upDezesseis($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campo12, $campo13, $campo14, $campo15, $campo16, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeu12, $valeu13, $valeu14, $valeu15, $valeu16, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ?, $campo12 = ?, $campo13 = ?, $campo14 = ?, $campo15 = ?, $campo16 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeu12, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $valeu13, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $valeu14, PDO::PARAM_STR);
        $sqUpdate->bindValue(15, $valeu15, PDO::PARAM_STR);
        $sqUpdate->bindValue(16, $valeu16, PDO::PARAM_INT);
        $sqUpdate->bindValue(17, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upDezessete($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campo12, $campo13, $campo14, $campo15, $campo16, $campo17, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeu12, $valeu13, $valeu14, $valeu15, $valeu16, $valeu17, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ?, $campo12 = ?, $campo13 = ?, $campo14 = ?, $campo15 = ?, $campo16 = ?, $campo17 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeu12, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $valeu13, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $valeu14, PDO::PARAM_STR);
        $sqUpdate->bindValue(15, $valeu15, PDO::PARAM_STR);
        $sqUpdate->bindValue(16, $valeu16, PDO::PARAM_STR);
        $sqUpdate->bindValue(17, $valeu17, PDO::PARAM_INT);
        $sqUpdate->bindValue(18, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function upDezoito($tabela, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $campo7, $campo8, $campo9, $campo10, $campo11, $campo12, $campo13, $campo14, $campo15, $campo16, $campo17, $campo18, $campoId, $valeu1, $valeu2, $valeu3, $valeu4, $valeu5, $valeu6, $valeu7, $valeu8, $valeu9, $valeu10, $valeu11, $valeu12, $valeu13, $valeu14, $valeu15, $valeu16, $valeu17, $valeu18, $valeuId)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqUpdate = $conn->prepare("UPDATE $tabela SET $campo1 = ?, $campo2 = ?, $campo3 = ?, $campo4 = ?, $campo5 = ?, $campo6 = ?, $campo7 = ?, $campo8 = ?, $campo9 = ?, $campo10 = ?, $campo11 = ?, $campo12 = ?, $campo13 = ?, $campo14 = ?, $campo15 = ?, $campo16 = ?, $campo17 = ?, $campo18 = ? WHERE $campoId = ? ");
        $sqUpdate->bindValue(1, $valeu1, PDO::PARAM_STR);
        $sqUpdate->bindValue(2, $valeu2, PDO::PARAM_STR);
        $sqUpdate->bindValue(3, $valeu3, PDO::PARAM_STR);
        $sqUpdate->bindValue(4, $valeu4, PDO::PARAM_STR);
        $sqUpdate->bindValue(5, $valeu5, PDO::PARAM_STR);
        $sqUpdate->bindValue(6, $valeu6, PDO::PARAM_STR);
        $sqUpdate->bindValue(7, $valeu7, PDO::PARAM_STR);
        $sqUpdate->bindValue(8, $valeu8, PDO::PARAM_STR);
        $sqUpdate->bindValue(9, $valeu9, PDO::PARAM_STR);
        $sqUpdate->bindValue(10, $valeu10, PDO::PARAM_STR);
        $sqUpdate->bindValue(11, $valeu11, PDO::PARAM_STR);
        $sqUpdate->bindValue(12, $valeu12, PDO::PARAM_STR);
        $sqUpdate->bindValue(13, $valeu13, PDO::PARAM_STR);
        $sqUpdate->bindValue(14, $valeu14, PDO::PARAM_STR);
        $sqUpdate->bindValue(15, $valeu15, PDO::PARAM_STR);
        $sqUpdate->bindValue(16, $valeu16, PDO::PARAM_STR);
        $sqUpdate->bindValue(17, $valeu17, PDO::PARAM_STR);
        $sqUpdate->bindValue(18, $valeu18, PDO::PARAM_STR);
        $sqUpdate->bindValue(19, $valeuId, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Atualizado';
        } else {
            return 'nAtualizado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//-------------------------------------------DELETE---------------------------------------------------------------------
//Delete Registro
function deleteRegistro($tabela, $campoReferencia, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqUpdate = $conn->prepare("DELETE FROM $tabela WHERE $campoReferencia = ?");
        $sqUpdate->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Deletado';
        } else {
            return 'nDeletado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

function deleteRegistroPizza($tabela, $campoReferencia, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqDelete = $conn->prepare("DELETE FROM $tabela WHERE $campoReferencia = ?");
        $sqDelete->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqDelete->execute();
        $conn->commit();
        if ($sqDelete->rowCount() > 0) {
            return 'Deletado';
        } else {
            return 'nDeletado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//Delete Registro Unico
function deleteRegistroUnico($tabela, $campoReferencia, $idparametro)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();

        $sqUpdate = $conn->prepare("DELETE FROM $tabela WHERE $campoReferencia = ?");
        $sqUpdate->bindValue(1, $idparametro, PDO::PARAM_INT);
        $sqUpdate->execute();
        $conn->commit();
        if ($sqUpdate->rowCount() > 0) {
            return 'Deletado com Sucesso';
        } else {
            return 'Nao Deletado';
        };
    } catch (PDOException $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
    $conn = null;
}

//-------------------------------------------OUTROS---------------------------------------------------------------------
//destruir sessao e redirecionar pagina
function destruirSessaoRedirecionarCrud($paginaRedirecionada)
{
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    };
    session_destroy();
    header("Location: $paginaRedirecionada");
}

//converter texto para maiusuclo
function textoMaiusculo($texto)
{
    $retorno = mb_strtoupper("$texto", 'UTF-8');
    return $retorno;
}

function codificar($codificar, $tipo)
{
    if ($tipo == 'codificar') {
        return base64_encode($codificar);
    } else {
        return base64_decode($codificar);
    };
}

//abreviar nome
function abreviarNome($Nome)
{
    // Calcula a quantidade de caracteres do nome
    $quantidade = strlen($Nome);
    //Variavel para fazer a comparacao se passou da quantidade maxima permitida
    $maximo_caracter = 20;
    // if para fazer a comparação e decidir se é necessario fazer o tratamento do nome
    if ($quantidade < $maximo_caracter) {
        return $Nome;
    }

    $Nome = explode(" ", $Nome); // cria o array $nome com as partes da string
    $num = count($Nome); // conta quantas partes o nome tem
    $novo_nome = '';
    // variavel que irá concatenar as partes do nome
    $espacos = " ";

    //Variaveis para controle qual sobrenome o foreach está
    $count = 1;
    foreach ($Nome as $var) { // loop no array
        //echo "<br/> Num ".$num."Count ".$count;
        if (($count == 1) || ($count == $num)) {
            $novo_nome .= $var . ' '; // Atribui o primeiro nome
            //$count++;
        }


        //Quando for para segunda posição do array, que é o primeiro sobrenome e que não
        //seja maior do que a quantidade de sobrenome do nome

        if (($count >= 2) && ($count < $num)) {
            // Quando aparecer um desses entao nao atribui
            $array = array('do', 'Do', 'DO', 'da', 'Da', 'DA', 'de', 'De', 'DE', 'dos', 'Dos', 'DOS', 'das', 'Das', 'DAS');
            //Compara se a variavel var do foreach tem algum dos conteudos nao permitos
            //do array
            if (in_array($var, $array)) {
                // não Atribui para o nome novo
            } else {
                $novo_nome .= substr($var, 0, 1) . '. '; // abreviou
            } // fim
        }

        $count++;
    } //Final do Foreach
    return $novo_nome;
}

//mostrar idade da pessoa completa ano mes e dia
function mostrarIdade($dataAniversario)
{
    $date = new DateTime("$dataAniversario"); // data de nascimento
    $interval = $date->diff(new DateTime(date('Y-m-d'))); // data definida

    return $interval->format('%Y Anos, %m Meses e %d Dias'); // 110 Anos, 2 Meses e 2 Dias
}

function mostrarIdadeSomente($dataAniversario)
{
    $date = new DateTime("$dataAniversario"); // data de nascimento
    $interval = $date->diff(new DateTime(date('Y-m-d'))); // data definida

    return $interval->format('%Y');
}

//limitar texto sem cortar a palavra
function limitarTexto($texto, $limite)
{
    $contador = strlen($texto);
    if ($contador >= $limite) {
        $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
        return $texto;
    } else {
        return $texto;
    }
}

//verificar campo vazio do formulario
function campovazio(array $campos)
{
    $camposVazios = array();
    foreach ($campos as $legenda => $campo) {
        $name = $campo['input'];
        if (empty($_POST[$name])) {
            $camposVazios[] = sprintf("%s", $legenda);
        }
    }
    if (count($camposVazios) > 0) {
        return implode(", ", $camposVazios);
    } else {
        return 1;
    }
}

//destruir todas as sessoes e deslogar diretorio recuado
function destruirSessaoDiretorio()
{
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    };
    session_destroy();
    header('location:../index.php');
    exit;
}

//destruir todas as sessoes e deslogar
function destruirSessao()
{
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    };
    session_destroy();
    header('location:index.php');
    exit;
}

//calcular frete
function calculaFretePainel($cepDestino, $nCdServico)
{
    $cep_destino = $cepDestino; // CEP do cliente, que irá vim via POST
    $parametros = array();

    // Código e senha da empresa, se você tiver contrato com os correios, se não tiver deixe vazio.
    //    $parametros['nCdEmpresa'] = '20047312';
    //    $parametros['sDsSenha'] = 'h7Y70';
    $parametros['nCdEmpresa'] = '21126380';
    $parametros['sDsSenha'] = 'u8R02';
    //--------------------teste--------------------
    //    $parametros['nCdEmpresa'] = '08082650';
    //    $parametros['sDsSenha'] = '564321';
    //    -----------------------------------------
    // CEP de origem e destino. Esse parametro precisa ser numérico, sem "-" (hífen) espaços ou algo diferente de um número.
    $parametros['sCepOrigem'] = '35010161';
    $parametros['sCepDestino'] = $cepDestino;

    // O peso do produto deverá ser enviado em quilogramas, leve em consideração que isso deverá incluir o peso da embalagem.
    $parametros['nVlPeso'] = '0,08';

    // O formato tem apenas duas opções: 1 para caixa / pacote e 2 para rolo/prisma.
    $parametros['nCdFormato'] = '1';

    // O comprimento, altura, largura e diametro deverá ser informado em centímetros e somente números
    $parametros['nVlComprimento'] = '18';
    $parametros['nVlAltura'] = '4';
    $parametros['nVlLargura'] = '15';
    $parametros['nVlDiametro'] = '0';

    // Aqui você informa se quer que a encomenda deva ser entregue somente para uma determinada pessoa após confirmação por RG. Use "s" e "n".
    $parametros['sCdMaoPropria'] = 'n';

    // O valor declarado serve para o caso de sua encomenda extraviar, então você poderá recuperar o valor dela. Vale lembrar que o valor da encomenda interfere no valor do frete. Se não quiser declarar pode passar 0 (zero).
    $parametros['nVlValorDeclarado'] = '0';

    // Se você quer ser avisado sobre a entrega da encomenda. Para não avisar use "n", para avisar use "s".
    $parametros['sCdAvisoRecebimento'] = 'n';

    // Formato no qual a consulta será retornada, podendo ser: Popup é mostra uma janela pop-up - URL é envia os dados via post para a URL informada - XML é Retorna a resposta em XML
    $parametros['StrRetorno'] = 'xml';

    // Código do Serviço, pode ser apenas um ou mais. Para mais de um apenas separe por virgula.
    //    $parametros['nCdServico'] = '04014,04510';
    $parametros['nCdServico'] = $nCdServico;

    $parametros = http_build_query($parametros);
    $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
    $curl = curl_init($url . '?' . $parametros);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $dados = curl_exec($curl);
    $dados = simplexml_load_string($dados);
    foreach ($dados->cServico as $linhas) {
        if ($linhas->Erro == 0) {
            if ($linhas->Codigo == '04014') {
                $tipoFrete = 'SEDEX';
                $valorFrete = virgulaporpontoPainel($linhas->Valor);
                echo <<<EOT
        $tipoFrete - CEP: $cepDestino R$ <input type="hidden" value="$valorFrete" id="valorFrete" name="valorFrete"><strong class='font-16'>$linhas->Valor</strong><br>Prazo: $linhas->PrazoEntrega dias.
        
EOT;
            } else if ($linhas->Codigo == '04510') {
                $tipoFrete = 'PAC';
                $valorFrete = virgulaporpontoPainel($linhas->Valor);
                echo <<<EOT
        $tipoFrete - CEP: $cepDestino R$ <input type="hidden" value="$valorFrete" id="valorFrete" name="valorFrete"><strong class='font-16'>$linhas->Valor</strong><br>Prazo: $linhas->PrazoEntrega dias.
        
EOT;
            } else if ($linhas->Codigo == '04235') {
                $tipoFrete = 'PAC-MINI';
                $valorFrete = virgulaporpontoPainel($linhas->Valor);
                echo <<<EOT
        $tipoFrete - CEP: $cepDestino R$ <input type="hidden" value="$valorFrete" id="valorFrete" name="valorFrete"><strong class='font-16'>$linhas->Valor</strong><br>Prazo: $linhas->PrazoEntrega dias.
        
EOT;
            };
        } else {
            echo $linhas->MsgErro;
        };
        echo '<hr>';
    };
}

function virgulaporpontoPainel($str)
{
    $str = str_replace(",", ".", $str);
    return $str;
}

function formatarDataHoraBr($data)
{
    return date('d/m/Y H:i:s', strtotime($data));
}

function formatarDataBr($data)
{
    return date('d/m/Y', strtotime($data));
}

function formatarDataHoraEn($data)
{
    return date('Y-m-d H:i:s', strtotime($data));
}

function formatarDataEn($data)
{
    return date('Y-m-d', strtotime($data));
}

function uploadSimples($diretorioImg, $nameInputFile, $tipoUploadDocOUimg, $tipoUpload, $prefixoArquivo)
{
    //tipo de arquivo aceito doc|txt|pdf|xls|htm|html|rtf
    $dia = date('d');
    $mes = date('m');
    $ano = date('Y');
    $dataLegenda = $dia . $mes . $ano;
    $diretorio = $diretorioImg;
    if (!is_dir($diretorio)) {
        return "PastaNexiste";
    } else {
        $imgPrincipal = isset($_FILES["$nameInputFile"]) ? $_FILES["$nameInputFile"] : FALSE;
        if ($imgPrincipal['error'] == 4) {
            return "imgInexistente";
        } else {
            $nomeeTipoPrincipal = explode('.', $imgPrincipal['name']);
            if ($tipoUploadDocOUimg == "DOCUMENTO") {
                if (preg_match("/^(.*)\.($tipoUpload)$/", $imgPrincipal['name'])) {
                    $imageNamePrincipal = $prefixoArquivo . "_" . rand() . '_' . $dataLegenda . '.' . $nomeeTipoPrincipal[1];
                    $destinoPrincipal = $diretorio . "/" . $imageNamePrincipal;
                    if (move_uploaded_file($imgPrincipal['tmp_name'], $destinoPrincipal)) {
                        return "$imageNamePrincipal";
                    } else {
                        return "nEnviado";
                    }
                } else {
                    return "nEnviadoTipoInesperadoDoc";
                }
            } else if ($tipoUploadDocOUimg == "IMAGEM") {
                if (preg_match("/^image\/($tipoUpload)$/", $imgPrincipal['type'])) {
                    $imageNamePrincipal = $prefixoArquivo . "_" . rand() . '_' . $dataLegenda . '.' . $nomeeTipoPrincipal[1];
                    $destinoPrincipal = $diretorio . "/" . $imageNamePrincipal;
                    if (move_uploaded_file($imgPrincipal['tmp_name'], $destinoPrincipal)) {
                        return "$imageNamePrincipal";
                    } else {
                        return "nEnviado";
                    }
                } else {
                    return "nEnviadoTipoInesperadoImg";
                }
            } else {
                if (preg_match("/^(.*)\.(doc|txt|pdf|xls|htm|html|rtf)$/", $imgPrincipal['type'])) {
                    $imageNamePrincipal = 'Exclusivy_' . rand() . '_' . $dataLegenda . '.' . $nomeeTipoPrincipal[1];
                    $destinoPrincipal = $diretorio . "/" . $imageNamePrincipal;
                } else if (preg_match("/^image\/(gif|jpeg|jpg|png)$/", $imgPrincipal['name'])) {
                    $imageNamePrincipal = 'Exclusivy_' . rand() . '_' . $dataLegenda . '.' . $nomeeTipoPrincipal[1];
                    $destinoPrincipal = $diretorio . "/" . $imageNamePrincipal;
                } else {
                    $imageNamePrincipal = 'Exclusivy_' . rand() . '_' . $dataLegenda . '.jpg';
                    $destinoPrincipal = $diretorio . "/" . $imageNamePrincipal;
                }
                if (move_uploaded_file($imgPrincipal['tmp_name'], $destinoPrincipal)) {
                    return "$imageNamePrincipal";
                } else {
                    return "nEnviado";
                }
            }
        }
    }
}

function uploadSimplesArray($diretorioImg, $nameInputFile, $tipoUploadDocOUimg, $tipoUpload, $prefixoArquivo)
{
    //tipo de arquivo aceito doc|txt|pdf|xls|htm|html|rtf
    $dia = date('d');
    $mes = date('m');
    $ano = date('Y');
    $dataLegenda = $dia . $mes . $ano;
    $diretorio = $diretorioImg;
    if (!is_dir($diretorio)) {
        return "PastaNexiste";
    } else {
        $arquivo = isset($_FILES["$nameInputFile"]) ? $_FILES["$nameInputFile"] : FALSE;
        if ($arquivo['error'] == 4) {
            return "imgInexistente";
        } else {
            for ($controle = 0; $controle < count($arquivo['name']); $controle++) {
                $nomeArq = $arquivo["name"][$controle];
                $tamanhoArq = $arquivo["size"][$controle];
                $tipoArq = $arquivo["type"][$controle];
                $tmpnameArq = $arquivo["tmp_name"][$controle];
                $nomeETipo = explode('.', $nomeArq);

                if ($tipoUploadDocOUimg == "DOCUMENTO") {
                    if (preg_match("/^(.*)\.($tipoUpload)$/", $nomeArq)) {
                        $imageName = $prefixoArquivo . '_' . rand() . '_' . $dataLegenda . '.' . $nomeETipo[1];
                        $destino = $diretorio . "/" . $imageName;
                        if (move_uploaded_file($arquivo['tmp_name'][$controle], $destino)) {
                            return "Enviado";
                        } else {
                            return "nEnviado";
                        }
                    } else {
                        return "nEnviadoTipoInesperadoDoc";
                    }
                } else if ($tipoUploadDocOUimg == "IMAGEM") {
                    if (preg_match("/^image\/($tipoUpload)$/", $arquivo["type"][$controle])) {
                        $imageName = $prefixoArquivo . '_' . rand() . '_' . $dataLegenda . '.' . $nomeETipo[1];
                        $destino = $diretorio . "/" . $imageName;
                        if (move_uploaded_file($arquivo['tmp_name'][$controle], $destino)) {
                            return "Enviado";
                        } else {
                            return "nEnviado";
                        }
                    } else {
                        return "nEnviadoTipoInesperadoImg";
                    }
                } else {
                    if (preg_match("/^(.*)\.(doc|txt|pdf|xls|htm|html|rtf)$/", $nomeArq)) {
                        $imageName = 'Exclusivy_' . rand() . '_' . $dataLegenda . '.' . $nomeETipo[1];
                        $destino = $diretorio . "/" . $imageName;
                    } else if (preg_match("/^image\/(gif|jpeg|jpg|png)$/", $nomeArq)) {
                        $imageName = 'Exclusivy_' . rand() . '_' . $dataLegenda . '.' . $nomeETipo[1];
                        $destino = $diretorio . "/" . $imageName;
                    } else {
                        $imageName = 'Exclusivy_' . rand() . '_' . $dataLegenda . '.jpg';
                        $destino = $diretorio . "/" . $imageName;
                    }
                    if (move_uploaded_file($arquivo['tmp_name'][$controle], $destino)) {
                        return "Enviado";
                    } else {
                        return "nEnviado";
                    }
                }
            }
        }
    }
}

function enviaEmail($nomeContato, $emailContato, $assunto, $celularContato, $mensagemContato)
{
    include_once('./phpmailer/hdw-phpmailer.php');
    $nome = trim($nomeContato);
    $email = trim($emailContato);
    $assunto = trim($assunto);
    $celular = trim($celularContato);
    $mensagem = trim($mensagemContato);

    date_default_timezone_set('America/Asuncion');
    $datahora = date('d/m/Y H:i:s');
    $IP = $_SERVER['REMOTE_ADDR'];
    $emailAssunto = $assunto;
    $emailMensagem = "<strong>{$emailAssunto}</strong><br /> <hr /><strong>Sr(a):</strong> {$nome}<br /><strong>Correo:</strong> {$email}<br />Célula:</strong> {$celular}<br /><br /><strong>Mensaje:</strong><br />{$mensagem}<br /><hr /><strong>Fecha/Hora:</strong> {$datahora}<br /><strong>IP:</strong> {$IP}<br /><br />";
    $emailDe = array();
    $emailDe['from'] = "{$email}";
    $emailDe['fromName'] = "{$nome}";
    $emailDe['replyTo'] = "{$email}";
    $emailDe['returnPath'] = 'retorno@publimeducp.org';
    $emailDe['confirmTo'] = 'retorno@publimeducp.org';
    $emailPara = array();
    $emailPara[1]['to'] = 'contato@publimeducp.org';
    $emailPara[1]['toName'] = 'Contato Site Núcleo de Investigación';
    $emailPara[2]['to'] = $email;
    $emailPara[2]['toName'] = $nome;

    // DADOS DA CONTA SMTP PARA AUTENTICACAO DO ENVIO
    $SMTP = array();
    $SMTP['host'] = 'mail.publimeducp.org';
    $SMTP['port'] = 587;
    //    $SMTP['port'] = 26; // para o gmail utilize 587
    $SMTP['encrypt'] = ''; // ssl ou tls ou vazio, para o gmail utilize tls
    $SMTP['username'] = 'contato@publimeducp.org'; // recomendamos criar uma conta de email somente para ser utilizada aqui
    $SMTP['password'] = 'Wold51@2#'; // pois cada vez que a senha for alterada este arquivo tambem devera ser atualizado
    $SMTP['charset'] = 'utf-8'; // 'utf-8' ou 'iso-8859-1', siga o padrao do arquivo para nao haver erros na acentuacao
    $SMTP['priority'] = 1; // prioridade: 1=alta; 3=normal; 5=baixa;


    // DEBUG (ajuda para descobrir erros)
    // - use TRUE para ver os erros de envio
    // - uma vez configurado e funcionando obrigatoriamente utilize FALSE
    $SMTP['debug'] = FALSE;


    // faz o envio
    $mail = sendEmail($emailDe, $emailPara, $emailAssunto, $emailMensagem, $SMTP);


    // em caso de erro
    if ($mail !== TRUE) {
        // redireciona ou exibe uma mensagem de erro
        //header('location: erro.html');
        //        echo('Nao foi possivel enviar a mensagem.<br />Erro: '.$mail);
        return 'NoEnvio';
    } else {
        return 1;
    };

    // em caso de sucesso
    // redireciona ou exibe a mensagem de agradecimento
    header('location: https://www.publimeducp.org/index.php#contact');
    exit;
}

function validaCPF($cpf)
{
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

function dataFinalSemana($day)
{

    $nameDay = date("D", strtotime($day));
    $fds = "N";

    if ($nameDay == "Sat" || $nameDay == "Sun") {
        $fds = "S";
    }

    return $fds;
}

function possuiCamposVaziosArray(array $arrayCampos): bool
{
    $filtro = array_filter($arrayCampos);
    $dif = array_diff($arrayCampos, $filtro);

    return count($dif) === 0 ? false : true;
}

function excluirDashboard($tabela, $campoid, $id)
{
    $conn = conectar();
    $queryListar = $conn->prepare("DELETE FROM $tabela WHERE $campoid = $id");
    $queryListar->execute();
    if ($queryListar->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function excluirPacotes($tabela, $campoid, $id)
{
    $conn = conectar();
    $queryListar = $conn->prepare("DELETE FROM $tabela WHERE $campoid = :id");
    $queryListar->bindValue(':id', $id, PDO::PARAM_INT);
    $queryListar->execute();
    if ($queryListar->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}




?>