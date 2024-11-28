<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
     
      
          $iduser = htmlspecialchars($_POST['iduser'],ENT_QUOTES,'UTF-8');
          $currentPassword=htmlspecialchars($_POST['currentpass'],ENT_QUOTES,'UTF-8');
          $newPassword = htmlspecialchars($_POST['newpass'],ENT_QUOTES,'UTF-8');

          $iduser=isset($iduser) && $iduser===1? $iduser:$_SESSION["iduser"];

        require '../../models/models_usuarios.php';
         $user = new Usuario();
         if($user->VerifyUserPassword($iduser, $currentPassword)){

            if($user->IsNewPasswordValid($iduser, $currentPassword, $newPassword)){

                $response =$user->ChangeUserPassword($iduser, $newPassword);
                session_destroy();//destruimos la session
               echo json_encode($response);

            }else{
             $response = array('status' => false, 'auth' => true, 'msg' => 'La contraseña ya ha sido utilizado.', 'data' => '');
              echo json_encode($response);
              return;
         }

         }else{
          $response = array('status' => false, 'auth' => true, 'msg' => 'La contraseña actual es incorecto.', 'data' => '');
           echo json_encode($response);
           return;
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
//listo
 ?>

