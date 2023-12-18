<?php
include_once "../config/constantes.php";
include_once "../config/conexao.php";
include_once "../func/dashboard.php";

// Receber a imagem
$imagem = filter_input(INPUT_POST, 'imagem', FILTER_DEFAULT);

// Caminho completo para o diretório
$caminho_diretorio = '../assets/images/produtos/';

// Variável para armazenar o nome da imagem
$imagem_nome = '';

// Se uma nova imagem foi enviada
if (!empty($imagem)) {
    // Separa as informações da imagem base64 pelo ";"
    list($type, $imagem) = explode(';', $imagem);
    list(, $imagem) = explode(',', $imagem);

    // Desconverter a imagem base64
    $imagem = base64_decode($imagem);

    // Atribuir um novo nome para a imagem PNG
    $imagem_nome = time() . '.png';

    // Realizar o upload da nova imagem
    file_put_contents($caminho_diretorio . $imagem_nome, $imagem);

    echo "Imagem enviada com sucesso!";
}

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if (!empty($dados) && isset($dados)) {

    $nomeProduto = $dados['nomeProduto'];
    $valorProduto = $dados['valorProduto'];
    $dataeHoraAtual = date('Y-m-d H:i:s');


    $resultado = insertQuatro(
        'produtos',
        'img, produto, valor, cadastro',
        $imagem_nome,
        $nomeProduto,
        $valorProduto,
        $dataeHoraAtual
    );

    echo "Registro inserido com sucesso!";
} else {
    echo "Erro no insert";
}
