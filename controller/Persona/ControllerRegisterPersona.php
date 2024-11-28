


<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
   setcookie("activo", 1, time() + 3600);
    require '../../models/models_persona.php';
    $persona = new Persona();


     $idPersona = htmlspecialchars($_POST['idPersona'],ENT_QUOTES,'UTF-8');
     $nombres = htmlspecialchars($_POST['nombres'],ENT_QUOTES,'UTF-8');
     $apellidos=htmlspecialchars($_POST['apellidos'],ENT_QUOTES,'UTF-8');
     $correo=htmlspecialchars($_POST['correo'],ENT_QUOTES,'UTF-8');
     $dni = htmlspecialchars($_POST['dni'],ENT_QUOTES,'UTF-8');
     $telefono=htmlspecialchars($_POST['telefono'],ENT_QUOTES,'UTF-8');
     $direccion=htmlspecialchars($_POST['direccion'],ENT_QUOTES,'UTF-8');
     $salario=htmlspecialchars($_POST['salario'],ENT_QUOTES,'UTF-8');
     $sexo = htmlspecialchars($_POST['sexo'],ENT_QUOTES,'UTF-8');
     $monedas=htmlspecialchars($_POST['monedas'],ENT_QUOTES,'UTF-8');
     $entrevista=htmlspecialchars($_POST['entrevista'],ENT_QUOTES,'UTF-8');
     $resulentrevistas=htmlspecialchars($_POST['resulentrevistas'],ENT_QUOTES,'UTF-8');
      $typePeople=htmlspecialchars($_POST['typePeople'],ENT_QUOTES,'UTF-8');


          require '../base/Controller_data.php';
          if (trim( $nombres ) !== clean_input_characters( $nombres )) {
           $response = array('status' => false, 'auth' => false, 'msg' => 'Bad Request', 'data' => '');
           echo json_encode($response);
           return;
         }

         //manegar photo
          require '../base/ControllerManagementFiles.php';
         if (isset($_FILES["foto"]["name"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
           $photoName= uploadPhotoAll("foto", $nombres,'people');
         }

         //manejador de cv
         if (isset($_FILES["files"]) ) {
           $documtname = uploadDocumentAll("files", $nombres,'people');
         }

          $filename = (isset($documtname) && $documtname) ? $documtname :  '';
          $imgname = (isset($photoName) && $photoName) ? $photoName :  '';

         $response = $persona->Registrar_Persona($nombres,$apellidos,$correo,$dni,$telefono,$direccion,$salario,$sexo,$monedas,$entrevista,$resulentrevistas,$filename,$imgname,$typePeople);
         echo $response;
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
