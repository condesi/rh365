<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     
    
        require '../../models/model_entry.php';
        $entry = new Entry();
        $id_user_loger= $_SESSION['iduser'];

        $response =$entry->automaticBackground();
       echo json_encode($response);
    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        http_response_code(403);
        echo json_encode($response);
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE GET.','data'=> '' ,'tipo'=>'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}

 ?>