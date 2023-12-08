<?php

include_once './config/constantes.php';
include_once './config/conexao.php';
include_once './func/dashboard.php';

// $eventosCalendario = listarGeral('idcalendario, title, color, start, end', 'calendario');

// QUERY para recuperar os eventos
$query_events = "SELECT id, title, color, start, end FROM calendario";

// prepara o QUERY
$result_events = $conn->prepare($query_events);

// executa o QUERY
$result_events->execute();

// criar o array que recebe os eventos
$eventos = [];

// percorre a lista de registros retornando do banco de dados
while($row_events = $result_events->fetch(PDO::FETCH_ASSOC)){

    // extrair o array
    extract($row_events);

    $eventos [] = [
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $start,
        'end' => $end,
    ];
}



echo json_encode($eventos);




// // criar o array que recebe os eventos
// $eventos = [];

// foreach($eventos as $listarEventos){

//     $eventos[] = 'id'->idcalendario;
//     $eventos[] = $title['title'];
//     $eventos[] = $color['color'];
//     $eventos[] = $start['start'];
//     $eventos[] = $end['end'];

//     // $eventos[] = [
//     //     'idcalendario'->$id,
//     //     'title'->$title,
//     //     'color'->$color,
//     //     'start'->$start,
//     //     'end'->$end
//     // ];
// }

// // echo json_encode($eventos);
// var_dump($eventos);



?>