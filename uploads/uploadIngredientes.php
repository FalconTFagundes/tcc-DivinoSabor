<?php
include_once "../config/constantes.php";
include_once "../config/conexao.php";
include_once "../func/dashboard.php";

// Receber a imagem
$imagem = filter_input(INPUT_POST, 'imagem', FILTER_DEFAULT);
//var_dump($imagem);

// Separa as informações da imagem base64 pelo ";"
list($type, $imagem) = explode(';', $imagem);
list(, $imagem) = explode(',', $imagem);

// Desconverter a imagem base64
$imagem = base64_decode($imagem);

// Atribuir a extensão da imagem PNG
$imagem_nome = time() . '.png';

// Caminho completo para o diretório
$caminho_diretorio = '../assets/images/ingredientes/';

// Realizar o upload da imagem
file_put_contents($caminho_diretorio . $imagem_nome, $imagem);

echo "Imagem enviada com sucesso!";



$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$conn = conectar();

if (!empty($dados) && isset($dados)) {

    $nomeIngrediente = $dados['nomeIngred'];
    $qtdIngrediente = $dados['quantIngred'];
    $pesoIngrediente = $dados['pesoIngred'];
    $valorIngred = $dados['valorIngred'];
    $dataCompraIngrediente = $dados['dataCompra'];
    $codigoIngrediente = $dados['codigoIngrediente'];
    $dataValidade = $dados['dataValidade'];

    $dataeHoraAtual = date('Y-m-d H:i:s');

    var_dump($dados);

    $resultado =  insertNove(
        'ingredientes',
        'nomeIngred, img, quantIngred, pesoUnit, precoUnit, dataComp, dataValidad, codigo, cadastro',
        "$nomeIngrediente",
        "$imagem_nome",
        "$qtdIngrediente",
        "$pesoIngrediente",
        "$valorIngred",
        "$dataCompraIngrediente",
        "$dataValidade",
        "$codigoIngrediente",
        "$dataeHoraAtual"
    );
} else {
    echo json_encode('Erro no Insert');
}
