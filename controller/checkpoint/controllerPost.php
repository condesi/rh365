<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

  try {
        $id = isset($_POST['id']) ? htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8') : '';
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $longitude = htmlspecialchars($_POST['longitude'], ENT_QUOTES, 'UTF-8');
        $latitude = htmlspecialchars($_POST['latitude'], ENT_QUOTES, 'UTF-8');
        $haversine = htmlspecialchars($_POST['haversine'], ENT_QUOTES, 'UTF-8');
        $threshold = htmlspecialchars($_POST['threshold'], ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8');
        $description =isset($_POST['description']) ?  htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : '';
        $photo = isset($_POST['photo']) ?  htmlspecialchars($_POST['photo'], ENT_QUOTES, 'UTF-8') : '';
        

        // Verifica que los campos obligatorios no estén vacíos
        if (empty($name))   throw new Exception("El nombre es un campo requerido", 1);
        if (empty($longitude))  throw new Exception("La longitud es un campo requerido", 1);
        if (empty($latitude))  throw new Exception("La latitud es un campo requerido", 1);
        if (empty($threshold))  throw new Exception("El umbral es un campo requerido", 1);

        if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
           require '../base/ControllerManagementFiles.php';
           $fileName= uploadPhotoAll("photo", $name,'checkpoint');
         }
          $photo = (isset($fileName) && $fileName) ? $fileName : '';
        
        require '../../models/model_checkpoint.php';
        $checkpointModel = new Checkpoint();
        if (empty($id)) {
            $id_user=null;
            $response = $checkpointModel->registerCheckpoint($id, $id_user, $name, $longitude, $latitude, $haversine, $threshold, $status, $description, $photo);
        } else {
            $response = $checkpointModel->updateCheckpoint($id, $name, $longitude, $latitude, $haversine, $threshold, $status, $description, $photo);
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