<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_SESSION['username'])) {
            setcookie("activo", 1, time() + 3600);

            require '../../models/models_usuarios.php';
            $user = new Usuario();
            $consulta = $user->GetUsers();

            if ($consulta) {
                echo json_encode($consulta);
            } else {
                echo '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
            }
        } else {
            $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
            http_response_code(403);
            echo json_encode($response);
        }
    } catch (Exception $e) {
        // Manejar la excepción aquí
        $response = array('status' => false, 'auth' => false, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '');
        http_response_code(500);
        echo json_encode($response);
    }
} else {
    $response = array('status' => false, 'auth' => false, 'msg' => 'SOLO SE PUEDE POST.', 'data' => '', 'tipo' => 'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}
?>



