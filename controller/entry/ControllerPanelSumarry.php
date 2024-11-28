<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_SESSION['username'])) {
            setcookie("activo", 1, time() + 3600);
            require '../../models/model_entry.php';
            $entry = new Entry();

            $isadmi = ($_SESSION["iduser"] === 1) ? '' : $_SESSION["iduser"];
            $consulta = $entry->ListAllHoursGroupMonth($isadmi);

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
            echo json_encode($response);
        }
    } catch (Exception $e) {
        $response = array('status' => false, 'auth' => false, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '', 'tipo' => 'alert-danger');
        echo json_encode($response);
    }
} else {
    $response = array('status' => false, 'auth' => false, 'msg' => 'SOLO SE PUEDE POST.', 'data' => '', 'tipo' => 'alert-danger');
    echo json_encode($response);
}
?>
