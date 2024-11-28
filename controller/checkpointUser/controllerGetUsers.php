<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

  try {
       
        $id = isset($_GET['id']) ? $_GET['id'] : "";
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

    } catch (Exception $e) {
            $response = array('status' => false, 'auth' => false, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '');
            echo json_encode($response);
        }

    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        echo json_encode($response);
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE GET.','data'=> '' ,'tipo'=>'alert-danger');
    echo json_encode($response);
}

 ?>