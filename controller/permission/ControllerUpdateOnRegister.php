<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     try {
     $id =isset($_POST['id']) ? $_POST['id'] : null;

      $name=htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');
      $day=htmlspecialchars($_POST['day'],ENT_QUOTES,'UTF-8');
      $status=htmlspecialchars($_POST['status'],ENT_QUOTES,'UTF-8');
     $description=htmlspecialchars($_POST['description'],ENT_QUOTES,'UTF-8');
     $photo="";
     
      require '../../models/model_permission.php';
        $permission = new Permission();
        if(empty($id)) $permission->Register_Type_Permission($id, $name, $day, $description, $photo, $status=1);
        else $response =$permission->Update_Type_Permission($id, $name, $day, $description, $photo, $status);

         echo json_encode($response);
        } catch (Exception $e) {
        $response = array('status' => false, 'auth' => false, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
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