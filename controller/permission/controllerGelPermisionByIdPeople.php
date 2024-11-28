<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
    
      require '../../models/model_permission.php';
        $permission = new Permission();
        try{

         $idpersona = isset($_GET['idpersona']) ? htmlspecialchars($_GET['idpersona']) : null;
        $idpermission = isset($_GET['id_permission']) ? htmlspecialchars($_GET['id_permission']) : null;

        $consulta =$permission->getPermiByidPeopleAndByIdPermission($idpersona,$idpermission);
        echo json_encode($consulta);
        

     } catch (Exception $e) {
            $response = array('status' => false, 'auth' => false, 'msg' => $e->getMessage(), 'data' => '', 'tipo' => 'alert-danger');
            
            echo json_encode($response);
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