<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['username'])) {
        setcookie("activo", 1, time() + 3600);
        require '../../models/models_shifts.php';
        $shift = new Shift();
          $type=htmlspecialchars($_POST['type'],ENT_QUOTES,'UTF-8');
        $responce = $shift->ShowShiftsNormal($type);
      echo json_encode($responce);

    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        http_response_code(403);
        echo json_encode($response);
        return;
    }
} else {
    $response = array('status' => false, 'auth' => false, 'msg' => 'Método no permitido', 'data' => '', 'tipo' => 'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}
?>
