<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
  session_start();

  require '../../models/modelo_vacaciones.php';
  $vacacion = new Vacaciones;


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
  if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
  } else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
  }

  $consulta = $vacacion->Personal_Vacaciones_backgroundRunn();

  date_default_timezone_set('America/Lima');
  
  $fechaActual = date('Y-m-d');
  
  if (isset($consulta)) {

    foreach ($consulta as   $elemt) {

      if ($elemt["fechafinal"] <= $fechaActual) {

       $request= $vacacion->Listar_Empleados_Cambiar_Estado( $elemt["idempleado"],"Presente");
     }else{
      $request = 0 ;
    }
  }
}
if (isset($request)) {
  // La variable $request está definida
  // Puedes realizar las operaciones necesarias con la variable
  echo json_encode($request);
} else {
  // La variable $request no está definida
  // Puedes mostrar un mensaje de error o realizar otra acción
  echo json_encode($consulta);
}



} else {
  
  $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
  echo json_encode($response);
}

?>