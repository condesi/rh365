<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     
     $id = htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8');
     $namerole=htmlspecialchars($_POST['namerole'],ENT_QUOTES,'UTF-8');

        require '../base/Controller_data.php';
        if ($namerole !== clean_input_characters($namerole)) {
           $response = array('status' => false, 'auth' => false, 'msg' => 'Bad Request', 'data' => '');
            echo json_encode($response);
            return;
         }
        require '../../models/models_role.php';
        $role = new Role();
        $response =$role->Update_role($id,$namerole);
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