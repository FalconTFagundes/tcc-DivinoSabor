<?php
/**
 * Created by PhpStorm.
 * User: Luciano
 * Date: 19/10/2019
 * Time: 14:31
 */

//---------------VALIDAÇÕES----------------------------------------------------------

//VERIFICA o dominio se é interno ou externo, so pode se o dominio for interno
function verificarLink($refurl, $dominio)
{
    $urlConfirma = $refurl;
    $url = isset($urlConfirma) ? $urlConfirma : "";
    return trim((!empty($url) && strcasecmp($url, $dominio) == 0) ? true : false);
}

//VERIFICA usuario e senha
function verificarUsuarioSenha($email, $senha)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLoginEmail = $conn->prepare("SELECT idpessoa, email, nome, senha, tipousuario, ativo FROM pessoa WHERE email = ? ");
        $sqlLoginEmail->bindValue(1, $email, PDO::PARAM_STR);
        $sqlLoginEmail->execute();
        $conn->commit();
        if ($sqlLoginEmail->rowCount() == 1) {
            $rsLoginEmail = $sqlLoginEmail->fetch(PDO::FETCH_ASSOC);
            $senhaCadastro = $rsLoginEmail["senha"];
            $idpessoa = $rsLoginEmail["idpessoa"];
            $nomepessoa = $rsLoginEmail["nome"];
            $tipousuario = $rsLoginEmail["tipousuario"];
            $ativo = $rsLoginEmail["ativo"];
            if ($ativo == 'A') {
                if (password_verify($senha, $senhaCadastro)) {
                    $_SESSION['idpessoa'] = $idpessoa;
                    $_SESSION['tipousuario'] = $tipousuario;
                    $_SESSION['nomeusuario'] = $nomepessoa;
                    falhaLoginFalse($idpessoa);
                    insertContadorAcesso($idpessoa, $nomepessoa);
                    echo json_encode('loginTrue');
                } else {
                    $tentativas = verificaFalhaLogin($idpessoa);
                    if ($tentativas == 'Bloqueado') {
                        echo json_encode('Bloqueado');
                    } else {
                        falhaLogin($idpessoa);
                        session_destroy();
                        echo json_encode('loginFalse');
                    }
                };
            } else {
                echo json_encode('admDesativado');
            }
        } else {
            echo json_encode('emailFalse');
        }
    } catch
    (PDOExecption $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//VERIFICA falha de tentativas de login no sistema
function verificaFalhaLogin($idusuario)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlVerificaLoginFalha = $conn->prepare("SELECT falha, tempo FROM pessoa WHERE idpessoa = ? ");
        $sqlVerificaLoginFalha->bindValue(1, $idusuario, PDO::PARAM_INT);
        $sqlVerificaLoginFalha->execute();
        $conn->commit();
        if ($sqlVerificaLoginFalha->rowCount() == 1) {
            $rsVerificaLoginFalha = $sqlVerificaLoginFalha->fetch(PDO::FETCH_ASSOC);
            $numeroTentativas = $rsVerificaLoginFalha["falha"];
            $tempoBloqueio = $rsVerificaLoginFalha["tempo"];
            if ($numeroTentativas >= TENTATIVAFALHA) {
                if ($tempoBloqueio > DATATIMEATUAL) {
                    return 'Bloqueado';
                } else {
                    falhaLoginFalse($idusuario);
                };
            } else {
                return 'Liberado';
            }

        }
    } catch
    (PDOExecption $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//destruir sessao e redirecionar pagina
function destruirSessaoRedirecionar($paginaRedirecionada)
{
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    };
    session_destroy();
    header("Location: $paginaRedirecionada");
}

//pega o ip do cliente visitante
function pegaip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

//---------------INSERT----------------------------------------------------------

//INSERT acesso do administrador
function insertContadorAcesso($idadministrador, $nome)
{
    $conn = conectar();
    try {
        $ipusuario = pegaip();
        $conn->beginTransaction();
        $sqlInsertContador = $conn->prepare("INSERT INTO pessoaacesso (idpessoa, nome, cadastro, ip) VALUES(?,?,?,?)");
        $sqlInsertContador->bindValue(1, "$idadministrador", PDO::PARAM_INT);
        $sqlInsertContador->bindValue(2, "$nome", PDO::PARAM_STR);
        $sqlInsertContador->bindValue(3, DATATIMEATUAL, PDO::PARAM_STR);
        $sqlInsertContador->bindValue(4, "$ipusuario", PDO::PARAM_STR);
        $sqlInsertContador->execute();
        $conn->commit();
    } catch
    (PDOExecption $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//---------------UPDATE----------------------------------------------------------

//UPDATE no banco falha de login e tempo para bloquear sistema
function falhaLogin($idusuario)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $tempoFalha = TEMPOFALHA;
        $sqlLoginFalha = $conn->prepare("UPDATE pessoa SET falha = falha + 1, tempo = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL $tempoFalha MINUTE) WHERE idpessoa = ? ");
        $sqlLoginFalha->bindValue(1, $idusuario, PDO::PARAM_INT);
        $sqlLoginFalha->execute();
        $conn->commit();
    } catch
    (PDOExecption $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}

//UPDATE no banco para zerar falha e tempo das tentativas de login
function falhaLoginFalse($idusuario)
{
    $conn = conectar();
    try {
        $conn->beginTransaction();
        $sqlLoginFalha = $conn->prepare("UPDATE pessoa SET falha = 0,tempo = CURRENT_TIMESTAMP WHERE idpessoa = ? ");
        $sqlLoginFalha->bindValue(1, $idusuario, PDO::PARAM_INT);
        $sqlLoginFalha->execute();
        $conn->commit();
    } catch
    (PDOExecption $e) {
        echo 'Exception -> ';
        return ($e->getMessage());
        $conn->rollback();
    };
}
//---------------DELETE----------------------------------------------------------