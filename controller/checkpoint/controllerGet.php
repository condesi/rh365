<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

  try {

         $date_init = isset($_GET['params']['date_init']) ? $_GET['params']['date_init'] : null;
         $date_end = isset($_GET['params']['date_end']) ? $_GET['params']['date_end'] : null;
          $search = isset($_GET['params']['search']) ? $_GET['params']['search'] : null;
           $id = isset($_GET['id']) ? $_GET['id'] : "";

        require '../../models/model_checkpoint.php';
        $checkpointModel = new Checkpoint();
        
        $consulta = $checkpointModel->GetCheckpoints($search, $id);

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