<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './models/Evento.php';

$database = new Database();
$db = $database->getConnection();

$evento = new Evento($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        $stmt = $evento->read();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $eventos_arr = array();
            $eventos_arr["records"] = array();

            while ($row = $stmt->fetch_assoc()) {
                extract($row);
                $evento_item = array(
                    "id" => $id,
                    "nome" => $nome,
                    "descricao" => html_entity_decode($descricao),
                    "organizacao_id" => $organizacao_id,
                    "local_id" => $local_id,
                    "status" => $status,
                    "politica_cancelamento" => $politica_cancelamento,
                    "data_inicio" => $data_inicio,
                    "data_fim" => $data_fim
                );
                array_push($eventos_arr["records"], $evento_item);
            }

            http_response_code(200);
            echo json_encode($eventos_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Nenhum evento encontrado."));
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->nome) &&
            !empty($data->organizacao_id) &&
            !empty($data->local_id)
        ) {
            $evento->nome = $data->nome;
            $evento->descricao = $data->descricao;
            $evento->organizacao_id = $data->organizacao_id;
            $evento->local_id = $data->local_id;
            $evento->status = $data->status;
            $evento->politica_cancelamento = $data->politica_cancelamento;
            $evento->data_inicio = $data->data_inicio;
            $evento->data_fim = $data->data_fim;

            if ($evento->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Evento foi criado."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível criar o evento."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Não foi possível criar o evento. Dados incompletos."));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}

?>