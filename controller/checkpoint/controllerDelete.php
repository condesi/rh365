<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

  try {
      $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

        require '../../models/model_checkpoint.php';
        $checkpointModel = new Checkpoint();
       if (empty($id))   throw new Exception("El id es un campo requerido", 1);
        $consulta = $checkpointModel->removeCheckpoint($id);

        echo json_encode( $consulta);
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

 ?>