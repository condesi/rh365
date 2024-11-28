<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     
    
        require '../../models/model_permission.php';
        $permission = new Permission();
         date_default_timezone_set('America/Lima');
        $dateCurrent = new DateTime('now');
         $dateOnly = $dateCurrent->format('Y-m-d');

       try {

        $data =$permission->getPermissionsStaus_1( $dateOnly);
        $pesrmissis = $data['data'];
       if(!empty($pesrmissis)) {
           foreach ($pesrmissis as $item) {

             // Obtener la fecha de finalización del permiso
            $endDate = new DateTime($item['end_date']);

            // Comparar la fecha de finalización con el día actual
            if ($endDate < $dateCurrent) {
                // Si la fecha de finalización es anterior al día actual, actualizar el estado del permiso
                $status = 2;
                $response = $permission->updateStaurasPermission($status, $item['id'], $dateCurrent->format('Y'));
            }

           }
       }
        $datatimes = $permission->getPermissionTimeCurrenteMenor();
        $datatimes_ = !empty($datatimes) ? $datatimes['data'] : [];
        if(!empty($datatimes_)){
            foreach ($datatimes_ as $item) {
                $status= 2;
               $response= $permission->updateStaurasPermission($status ,$item['id'],$dateCurrent->format('Y'));
 
            }  

        }
        $response = empty($response)? array('status' => true, 'auth' => true, 'msg' => 'No hay nada para actualizar' , 'data' => '') : $response;

       echo json_encode($response);
       } catch (Exception $e) {
        // Manejar la excepción
        $response = array('status' => false, 'auth' => false, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        echo json_encode($response);
    }
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