<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './models/Setor.php';

$database = new Database();
$db = $database->getConnection();

$setor = new Setor($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if(isset($_GET['evento_id'])) {
            $setor->evento_id = $_GET['evento_id'];
            $stmt = $setor->readByEvento();
        } else {
            $stmt = $setor->read();
        }
        
        $num = $stmt->num_rows;

        if ($num > 0) {
            $setores_arr = array();
            $setores_arr["records"] = array();

            while ($row = $stmt->fetch_assoc()) {
                extract($row);
                $setor_item = array(
                    "id" => $id,
                    "evento_id" => $evento_id,
                    "nome" => $nome,
                    "capacidade" => $capacidade
                );
                array_push($setores_arr["records"], $setor_item);
            }

            http_response_code(200);
            echo json_encode($setores_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Nenhum setor encontrado."));
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->evento_id) &&
            !empty($data->nome) &&
            !empty($data->capacidade)
        ) {
            $setor->evento_id = $data->evento_id;
            $setor->nome = $data->nome;
            $setor->capacidade = $data->capacidade;

            if ($setor->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Setor foi criado."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível criar o setor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Não foi possível criar o setor. Dados incompletos."));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}
?>