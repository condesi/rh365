<?php
  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/models_persona.php';
    $persona = new Persona();


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

 $idpersona = htmlspecialchars($_POST['idpersona'],ENT_QUOTES,'UTF-8');
  $stado = htmlspecialchars($_POST['stado'],ENT_QUOTES,'UTF-8');

 $consulta = $persona->ChangeStatusPersonaOff($idpersona, $stado);
     echo $consulta;
    
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}
