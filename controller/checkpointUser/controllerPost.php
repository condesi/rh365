<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
      date_default_timezone_set('America/Lima');
       try {

          if (isset($_POST['points']))  $id_checkpoint = $_POST['points'];
            $id = isset($_POST['id']) ? htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8') : '';
            $id_user = htmlspecialchars($_POST['id_user'], ENT_QUOTES, 'UTF-8');
            $status = 1;
            $description = "";

            if(empty($id_user)) throw new Exception("El usuario es requeridos", 1);
            if(empty($id_checkpoint)) throw new Exception("Debes seleccionar al menos un punto de control", 1);

            require '../../models/model_checkpoint_user.php';
            $userPoit = new CheckpointUser();
            if (empty($id)) {

                foreach ($id_checkpoint as $item) {
           
                 if (!$userPoit->existeCheckpointUser($id_user, $item['id_checkpoint'])) {
              
                     $response = $userPoit->registerCheckpointUser($id_user, $item['id_checkpoint'], $status, $description);

                       if (!$response['status'])   throw new Exception('Error en el registro: ' . $response['msg']);

                        $response = $userPoit->updateStautsCheckpointByIdPoint($id_user,$item['id_checkpoint']);

                         if (!$response['status'])   throw new Exception('Error en el registro: ' . $response['msg']);
                
                  } else{
                    $response = array('status' => true, 'auth' => true, 'msg' => 'Algunos de los puestos ya pernecen al usuario', 'data' => ''); 
                  }
                }

            } else {
                $response = $userPoit->updateCheckpointUser($id, $id_user, $id_checkpoint, $status, $description);
            }
            echo json_encode($response);
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
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
    echo json_encode($response);
}

 ?>