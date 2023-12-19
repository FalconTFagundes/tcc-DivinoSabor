<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// confirmar se preencheram os dados no form
if (isset($dados['emailLogin']) && !empty($dados['emailLogin'])) {
    $email = $dados['emailLogin'];
} else {
    echo json_encode('Campos não preenchidos. Por favor, tente novamente.');
    die();
}

if (isset($dados['senhaLogin']) && !empty($dados['senhaLogin'])) {
    $senha = $dados['senhaLogin'];

    // $senha_hash = password_hash($senha, PASSWORD_DEFAULT); /* aqui capturo o hash que a senha digitada gera para salvar no banco */
    // echo $senha_hash;

} else {
    echo json_encode('Campos não preenchidos. Por favor, tente novamente.');
    die();
}

// confirmar se existe e estão corretos os dados do usuário no banco de dados
$checarDados = checarLogin('usuario', $email, $senha);

// informa os dados que estão errados para o pessoal da 4ª parede
if (!$checarDados) {
    echo json_encode('Email ou senha incorretos.');
    die();
} else {
    // variável para manter e mostrar a sessão
    $manterSessao = listarRegistroU('usuario', 'idusuario, nome', 'email', $email);

    if ($manterSessao == 'Vazio') {
        echo json_encode('Ocorreu um erro no servidor ao tentar fazer login.');
        die();
    } else {
        foreach ($manterSessao as $itemID) {
            $_SESSION['idUser'] = $itemID->idusuario;
            $_SESSION['nomeUser'] = $itemID->nome;
        }

        echo json_encode('OK');
        die();
    }
}
