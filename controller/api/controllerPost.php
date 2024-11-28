<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

// Obtener cabeceras y contenido de la solicitud
$headers = getallheaders();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Authorization, Content-Type");
    http_response_code(200);
    exit;
}

// Verificar el token de autenticación
if ($token == '123456') {
    // Verificar el método de solicitud
    if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD']== 'OPTIONS') {

        require '../../models/model_checkpoint.php';
        $checkpointModel = new Checkpoint();
        // Obtener datos JSON del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        $action = isset($data['action']) ? $data['action'] : '';
        $id=null;

        switch ($action) {
            case 'updateLocation':
                $userId = $data['userId'];
                $latitude = $data['latitude'];
                $longitude = $data['longitude'];
                $accuracy = $data['accuracy'];

                 $response = $checkpointModel->registerCheckpoint($id, null, 'MI-UBICACION-'. $userId, $longitude, $latitude, "", "", 1, 'mi ubicacion', '');
            echo json_encode($response);

                // Mostrar los datos recibidos
              /*  echo json_encode([
                    "message" => "Location received",
                    "userId" => $userId,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "accuracy" => $accuracy
                ]);*/
                break;

            default:
                http_response_code(400);
                echo json_encode(["message" => "Invalid action"]);
                break;
        }
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
    }
} else {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
}
?>





