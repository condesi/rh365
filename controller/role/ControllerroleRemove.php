<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     
     $idrole = htmlspecialchars($_POST['idrole'],ENT_QUOTES,'UTF-8');
    
        require '../base/Controller_data.php';
        if ($idrole !== clean_input_characters($idrole)) {
           $response = array('status' => false, 'auth' => false, 'msg' => 'Bad Request', 'data' => '');
       
            echo json_encode($response);
            return;
         }
        require '../../models/models_role.php';
        $role = new Role();
        $response =$role->Remove_role($idrole);
       echo json_encode($response);
    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        http_response_code(403);
        echo json_encode($response);
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}

 ?>