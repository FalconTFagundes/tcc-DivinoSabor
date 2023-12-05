<?php

function listarRegistros($campos, $tabela, $ativo)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela WHERE ativo = ? ");
        $sqlLista->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

function listarRegistrosU($campos, $tabela, $idcampo, $id)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela WHERE $idcampo = ? ");
        $sqlLista->bindValue(1, $id, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;


function listarRegistrosInner($campos, $tabela, $tabela2, $idcampo)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela INNER JOIN $tabela2 ON $tabela.$idcampo = $tabela2.$idcampo");
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function listarRegistrosInnerWhere($campos, $tabela, $tabela2, $idcampo, $tabelaW, $campoW, $idW)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela INNER JOIN $tabela2 ON $tabela.$idcampo = $tabela2.$idcampo WHERE $tabelaW.$campoW = $idW");
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function listarRegistrosPar($campos, $tabela, $ativo, $param2, $valp2)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela WHERE ativo = ? AND $param2 = ?");
        $sqlLista->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $valp2, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function listarRegPar($campos, $tabela, $param1, $val1, $param2, $val2)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela WHERE $param1 = ? AND $param2 = ?");
        $sqlLista->bindValue(1, $val1, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $val2, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function listarRegistrosPar2($campos, $tabela, $ativo, $param2, $valp2)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela WHERE ativo = ? AND $param2 = ?");
        $sqlLista->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $valp2, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}


function listarRegistrosDoisInt($campos, $tabela, $par1, $val1, $par2, $val2)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela WHERE $par1 = ? AND $par2 = ?");
        $sqlLista->bindValue(1, $val1, PDO::PARAM_INT);
        $sqlLista->bindValue(2, $val2, PDO::PARAM_INT);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

function listarRegistrosJoin($campos, $tabela, $join, $tabela2, $id, $ativo)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela $join JOIN $tabela2 ON $tabela.$id = $tabela2.$id AND $tabela.ativo = ? AND $tabela2.ativo = ?");
        $sqlLista->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $ativo, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function listarRegistrosJoin2A($campos, $tabela, $join, $tabela2, $id, $join2, $tabela3, $id2, $ativo)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela $join JOIN $tabela2 ON $tabela.$id = $tabela2.$id $join2 JOIN $tabela3 ON $tabela2.$id2 = $tabela3.$id2 AND $tabela.ativo = ? AND $tabela2.ativo = ? AND $tabela3.ativo = ?");
        $sqlLista->bindValue(1, $ativo, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $ativo, PDO::PARAM_STR);
        $sqlLista->bindValue(3, $ativo, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function listarRegistrosArte($titulo, $nome)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT tbusuario.nome, tbartesvend.idartesvend, tbartesvend.imgarte, tbartesvend.titulo, tbartesvend.descricao, tbartesvend.valor, tbartesvend.copias, tbvendedor.idvendedor
        FROM tbartesvend
        INNER JOIN tbvendedor on tbartesvend.idvendedor = tbvendedor.idvendedor
        INNER JOIN tbusuario on tbvendedor.idusuario = tbusuario.idusuario
        WHERE tbartesvend.titulo = ? and tbusuario.nome = ?");
        $sqlLista->bindValue(1, $titulo, PDO::PARAM_STR);
        $sqlLista->bindValue(2, $nome, PDO::PARAM_STR);
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;
function listarRegistrosJoin2($campos, $tabela, $join, $tabela2, $id, $join2, $tabela3, $id2)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("SELECT $campos FROM $tabela $join JOIN $tabela2 ON $tabela.$id = $tabela2.$id $join2 JOIN $tabela3 ON $tabela2.$id2 = $tabela3.$id2");
        $sqlLista->execute();
        if ($sqlLista->rowCount() > 0) {
            return $sqlLista->fetchAll(PDO::FETCH_OBJ);
        } else {
            return false;
        };
    } catch (PDOException $e) {
        return 'Não foi possível acessar os dados. Erro: ' . $e->getMessage();
    };
}

;

function inserirRegistrosReturnId($tabela, $campos, $valores)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("INSERT INTO $tabela ($campos) VALUES ($valores, NOW())");
        $resul = $sqlLista->execute();
        if ($resul === false) {
            $conn->rollback();
            return false;
        } else {
            $id = $conn->lastInsertId();
            return $id;
        };
    } catch (PDOException $e) {
        return 'Não foi possível cadastrar os dados. Erro: ' . $e->getMessage();
    };
}

;

function inserirRegistros($tabela, $campos, $valores)
{
    $conn = conectar();
    try {
        $sqlLista = $conn->prepare("INSERT INTO $tabela ($campos) VALUES ($valores)");
        $resul = $sqlLista->execute();
        if ($resul === false) {
            $conn->rollback();
            return false;
        } else {
            return true;
        };
    } catch (PDOException $e) {
        return 'Não foi possível cadastrar os dados. Erro: ' . $e->getMessage();
    };
}

;







?>