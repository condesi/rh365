<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     
     $code=htmlspecialchars($_POST['code'],ENT_QUOTES,'UTF-8');
     $type=htmlspecialchars($_POST['type'],ENT_QUOTES,'UTF-8');
     $id_user_loger=htmlspecialchars($_POST['id_user'],ENT_QUOTES,'UTF-8');
     $lat = null;
     $lng = null;
     $accuracy = null;

        require '../base/Controller_data.php';
        if (trim($code) !== clean_input_characters($code)) {
           $response = array('status' => false, 'auth' => false, 'msg' => 'Bad Request', 'data' => '');
            echo json_encode($response);
            return;
         }

        require '../../models/model_entry.php';
        $entry = new Entry();
        //$id_user_loger= $_SESSION['iduser'];

        $response =$entry->Register_Entry($code,$type,$id_user_loger ,$lat,$lng);
       echo json_encode($response);
    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        echo json_encode($response);
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
    echo json_encode($response);
}

 ?>