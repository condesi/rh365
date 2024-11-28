<?php
  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_pagosjornada.php';
     $pago = new Pagos;

if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return json_encode($response);
}

  $idpersona = htmlspecialchars($_POST['idpersona'], ENT_QUOTES, 'UTF-8');

  $consulta= $pago->ExtraerTotalCostoHorasExtrasPersona($idpersona);
   
    $response = array('status' => true,'auth' => true,'msg' => 'ok','data'=> $consulta);
    echo json_encode($response);
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}
