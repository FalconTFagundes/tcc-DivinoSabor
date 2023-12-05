<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
// echo json_encode($dados);
// confirmar se preencheram os dados no form

if (isset($dados['emailLogin']) && !empty($dados['emailLogin'])) {
    $email = $dados['emailLogin'];
} else {
    echo json_encode('Campos não preenchidos. Por favor, tente novamente.');
    die();
}

if (isset($dados['senhaLogin']) && !empty($dados['senhaLogin'])) {
    $senha = $dados['senhaLogin'];
} else {
    echo json_encode('Campos não preenchidos. Por favor, tente novamente.');
    die();
}

// echo json_encode($email." ".$senha);
// fim confirmar se preencheram os dados no form


// confirmar se existe e estão corretos os dados do usuário no banco de dados

$checarDados = checarLogin('usuario', $email, $senha);

// echo json_encode($checarDados);
// fim confirmar se existe e estão corretos os dados do usuário no banco de dados, clicar na func


// informa os dados que estão errados para o pessoal da 4ª parede
if ($checarDados == 'false') {
    echo json_encode('Email ou senha incorretos.');
    die();
} else if ($checarDados == 'OK') {
    // variável para manter e mostrar a sessão
    $manterSessao = listarRegistroU('usuario', 'idusuario, nome', 'email', $email);

    if ($manterSessao == 'Vazio') {
        echo json_encode('Ocorreu um erro no servidor ao tentar fazer login.');
        die();
    } else {
        foreach ($manterSessao as $itemID){

            $_SESSION['idUser'] = $itemID->idusuario;
            $_SESSION['nomeUser'] = $itemID->nome;
        }

        echo json_encode('OK');
        die();
    }
} else {
    echo json_encode('Ocorreu um erro no servidor ao tentar fazer login.');
    die();
}

// informa os dados que estão errados

?>

