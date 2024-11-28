<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
   setcookie("activo", 1, time() + 3600);


   $id = htmlspecialchars($_POST['iduser'],ENT_QUOTES,'UTF-8');
   $name=htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');
   $lastname = htmlspecialchars($_POST['lastname'],ENT_QUOTES,'UTF-8');
   $username = htmlspecialchars($_POST['username'],ENT_QUOTES,'UTF-8');
  $password = password_hash($_POST['passwordfirst'],PASSWORD_ARGON2I,['cost'=>10]); //PASSWORD_DEFAULT
  $role_id = htmlspecialchars($_POST['role_id'],ENT_QUOTES,'UTF-8');
  $code = htmlspecialchars($_POST['code'],ENT_QUOTES,'UTF-8');
  $people_id = htmlspecialchars($_POST['people_id'],ENT_QUOTES,'UTF-8');
  $company_id=1;//compania por defecto 1

          require '../base/Controller_data.php';
          if (trim( $username ) !== clean_input_characters( $username )) {
           $response = array('status' => false, 'auth' => false, 'msg' => 'Bad Request', 'data' => '');
           echo json_encode($response);
           return;
         }
         //manegar archivo
         if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
           require '../base/ControllerManagementFiles.php';
           $fileName= uploadPhotoAll("photo", $username,'user');
         }

          $photo = (isset($fileName) && $fileName) ? $fileName : '';
          
         require '../../models/models_usuarios.php';
         $user = new Usuario();
         $response =$user->RegisterUser($id,$name, $lastname, $username, $role_id, $photo, '', $password, '', $company_id,$code,$people_id);
         echo json_encode($response);
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



