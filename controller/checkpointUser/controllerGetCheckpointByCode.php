<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  try {

    $code =  htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8') ;
     if (empty($code))   throw new Exception("El código es requeridos", 1);
     require '../base/Controller_data.php';
     if (trim($code) !== clean_input_characters($code))   throw new Exception('Bad Request',1);

       require '../../models/model_entry.php';
        $entry = new Entry();

        $isExisteCode = $entry->verificationCode($code);
        if (!$isExisteCode)   throw new Exception("El código ingresado no existe", 1);
         $user = $entry->getUserByCode($code);
        if (!$user['status'] || !$user['auth']) throw new Exception("Usuario con ese código no existe.", 1); 
        $userBD = $user['data'];
        if (!isset($userBD['iduser']))  throw new Exception("iduser no definido en los datos del usuario.", 1);
         
         require '../../models/model_checkpoint_user.php';
         $checkpoint = new CheckpointUser();
         $response = $checkpoint->showCheckpointUser($userBD['iduser']);
         $response['user'] = $userBD;

        echo json_encode($response);
       
    } catch (Exception $e) {
            $response = array('status' => false, 'auth' => false, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '');
            echo json_encode($response);
        }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE GET.','data'=> '' );
    echo json_encode($response);
}

 ?>