<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

          require '../base/Controller_data.php';
         $idrole= clean_input_characters($_POST['id_role']);
         $access = isset($_POST['access']) ? $_POST['access'] : "";
          
         
           require '../../models/models_role.php';
           $role = new Role();

          if (!empty( $access) =="") {
           $response= $role->removeAllAccess($idrole);
            echo json_encode($response);
            return;
          }

          $accessUser = $role->getAccessForRole($idrole);
         foreach ($access as $acces) {
             if (!in_array($acces, $accessUser)) {
              $role->addAccess($idrole, $acces);
             }
             }

           foreach ($accessUser as $current) {
               if (!in_array($current, $access)) {
                 $role->removeAccess($idrole, $current);
               }
           }
          $response= array('status' => true, 'auth' => true, 'msg' => 'Los Accesos se actualizarón corectamente');
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
//listo
 ?>