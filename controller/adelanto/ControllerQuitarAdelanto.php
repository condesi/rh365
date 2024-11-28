<?php
  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_adelantos.php';
    $adelanto = new Adelantos();


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '','ultimoID'=>0);
    echo json_encode($response);
    return;
}

    $iddelantos = htmlspecialchars($_POST['iddelantos'],ENT_QUOTES,'UTF-8');
    $idepersona = htmlspecialchars($_POST['idepersona'],ENT_QUOTES,'UTF-8');
   $consulta = $adelanto->Quitar_Adelantos_Persona($idepersona,$iddelantos);
   echo $consulta;
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> '','ultimoID'=>0);
    echo json_encode($response);
}
