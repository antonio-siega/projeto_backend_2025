<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");

include_once './config/database.php';
include_once './models/Organizacao.php';

$database = new Database();
$db = $database->getConnection();
$org = new Organizacao($db);

$method = $_SERVER["REQUEST_METHOD"];

if ($method == 'GET') {
    $stmt = $org->read();
    $rows = [];
    while($row = $stmt->fetch_assoc()) $rows[] = $row;
    echo json_encode(["records" => $rows]);
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->nome)) {
        $org->nome = $data->nome;
        $org->contato = $data->contato ?? '';
        if($org->create()) echo json_encode(["message" => "Organização criada!"]);
        else echo json_encode(["message" => "Erro ao criar."]);
    }
}
?>