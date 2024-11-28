<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
   try {
       
        $id_user =  htmlspecialchars($_GET['id_user'], ENT_QUOTES, 'UTF-8') ;
         $id =  htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') ;
        if (empty($id_user))   throw new Exception("El usuario es requeridos", 1);
        if (empty($id))   throw new Exception("El key del registro es  requeridos", 1);
        
        require '../../models/model_checkpoint_user.php';
        $userPoint = new CheckpointUser();

          $id_checkpoint = $userPoint->idCheckPointCurrent($id);
          if(empty($id_checkpoint)) throw new Exception("No se encontro puesto de control", 1);

           $response = $userPoint->removeCheckpointUser($id);

             if (!$response['status'])   throw new Exception('Error al eliminar: ' . $response['msg']);

               $response = $userPoint->updateStautsCheckpointByIdPoint(0, $id_checkpoint);

               if (!$response['status'])   throw new Exception('Error al actualizar estado del puesto control: ' . $response['msg']);


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
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE GET.','data'=> '' ,'tipo'=>'alert-danger');
    echo json_encode($response);
}