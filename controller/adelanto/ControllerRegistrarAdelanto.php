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
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}


    $montoadelanto = htmlspecialchars($_POST['montoadelanto'],ENT_QUOTES,'UTF-8');
     $idpersona = htmlspecialchars($_POST['idpersona'],ENT_QUOTES,'UTF-8');
     date_default_timezone_set('America/Lima');
   $fechaActual = date('Y-m-d');
   $yearActual=date('Y');

   $consulta = $adelanto->Registrar_Adelantos($montoadelanto,$idpersona,$fechaActual,$yearActual);
    echo  $consulta;
    
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}
