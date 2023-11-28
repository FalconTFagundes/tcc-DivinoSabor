<?php
$listarAdminPessoa = validarSessao('index.php');
if ($listarAdminPessoa != 'Vazio') {
    foreach ($listarAdminPessoa as $itemDadosAdminPessoa) {
        $idpessoa = $itemDadosAdminPessoa->idpessoa;
        $nomePessoa = $itemDadosAdminPessoa->nome;
        $avatar = $itemDadosAdminPessoa->avatar;
        $email = $itemDadosAdminPessoa->email;
        $tipousuario = $itemDadosAdminPessoa->tipousuario;
    }
} else {
    $idpessoa = 0;
    $nomePessoa = '';
    $avatar = '';
    $email = '';
    $tipousuario = '';
}