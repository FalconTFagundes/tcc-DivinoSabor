<?php
include_once "../config/constantes.php";
include_once "../config/conexao.php";
include_once "../func/dashboard.php";

// Receber a imagem
$imagem = filter_input(INPUT_POST, 'imagem', FILTER_DEFAULT);

// Caminho completo para o diretório
$caminho_diretorio = '../assets/images/ingredientes/';

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
    $codigoIngrediente = $dados['codigoIngrediente'];

    // Verificar se o registro já existe
    $queryVerificacao = "SELECT COUNT(*) as total FROM ingredientes WHERE codigo = :codigo";
    $stmtVerificacao = $conn->prepare($queryVerificacao);
    $stmtVerificacao->bindParam(':codigo', $codigoIngrediente, PDO::PARAM_STR);
    $stmtVerificacao->execute();
    $resultadoVerificacao = $stmtVerificacao->fetch(PDO::FETCH_ASSOC);

    // Receber dados do $dados
    $nomeIngrediente = $dados['nomeIngred'];
    $qtdIngrediente = $dados['quantIngred'];
    $pesoIngrediente = $dados['pesoIngred'];
    $valorIngred = $dados['valorIngred'];
    $dataCompraIngrediente = $dados['dataCompra'];
    $dataValidade = $dados['dataValidade'];
    $dataeHoraAtual = date('Y-m-d H:i:s');

    // Se o registro já existe, faça o update
    if ($resultadoVerificacao['total'] > 0) {
        // Obter a quantidade atual
        $queryQuantidade = "SELECT quantIngred FROM ingredientes WHERE codigo = :codigo";
        $stmtQuantidade = $conn->prepare($queryQuantidade);
        $stmtQuantidade->bindParam(':codigo', $codigoIngrediente, PDO::PARAM_STR);
        $stmtQuantidade->execute();
        $quantidadeAtual = $stmtQuantidade->fetchColumn();

        // Somar a nova quantidade à quantidade existente
        $novaQuantidade = $qtdIngrediente + $quantidadeAtual;

        // Chamar a função de update
        upSeis(
            'ingredientes',
            'nomeIngred',
            'quantIngred',
            'pesoUnit',
            'precoUnit',
            'dataComp',
            'dataValidad',
            'codigo',
            $nomeIngrediente,
            $novaQuantidade,
            $pesoIngrediente,
            $valorIngred,
            $dataCompraIngrediente,
            $dataValidade,
            $codigoIngrediente
        );
    } else {
        // Caso não exista, faça o insert
        insertNove(
            'ingredientes',
            'nomeIngred, img, quantIngred, pesoUnit, precoUnit, dataComp, dataValidad, codigo, cadastro',
            $nomeIngrediente,
            $imagem_nome,
            $qtdIngrediente,
            $pesoIngrediente,
            $valorIngred,
            $dataCompraIngrediente,
            $dataValidade,
            $codigoIngrediente,
            $dataeHoraAtual
        );

        echo "Registro inserido com sucesso!";
    }
} else {
    echo json_encode('Erro no Insert');
}
