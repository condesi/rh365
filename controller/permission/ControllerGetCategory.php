<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     $date_init = isset($_POST['params']['date_init']) ? $_POST['params']['date_init'] : null;
     $date_end = isset($_POST['params']['date_end']) ? $_POST['params']['date_end'] : null;
      $search = isset($_POST['params']['search']) ? $_POST['params']['search'] : null;
     
        require '../../models/model_permission.php';
        $permission = new Permission();
        $consulta =$permission->getpermission($date_init,$date_end,$search);

        if($consulta){
        echo json_encode($consulta);
          }else{
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
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}

 ?>