<?php
  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_vacaciones.php';
    $vacations = new Vacaciones;


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

    $idpersona = htmlspecialchars($_POST['idpersona'],ENT_QUOTES,'UTF-8');
    $consulta = $vacations->Data_Show_Persona($idpersona);
        echo json_encode($consulta);
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}


