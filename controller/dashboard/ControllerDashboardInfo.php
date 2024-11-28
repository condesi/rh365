<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

      //iniciamos la sesion
  session_start();

  require '../../models/modelo_dashboard.php';
  $dashboard = new Dasboard();


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
  if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
  } else {

    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');

    return json_encode($response);
  }

  $consulta = $dashboard->DashBoardTableroInfo();
  echo  $consulta;



} else {

  $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
  echo json_encode($response);
}
?>