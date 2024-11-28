<?php
  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

 
    require '../../models/modelo_pagosjornada.php';
     $pago = new Pagos;



//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

  $consulta = $pago->Personal_jornadas_backgroundRunn();

    date_default_timezone_set('America/Lima');
     
     $fechaActual = date('Y-m-d');
     
    if (isset($consulta)) {

        foreach ($consulta as   $elemt) {

            if ($elemt["fechapago"] <= $fechaActual) {

               $request= $pago->Update_State_estado_CuentaPersonal($elemt["personaid"],"Deuda");
            }else{
                $request= $pago->Update_State_estado_CuentaPersonal($elemt["personaid"],"Pagado");
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
  echo 0;
}
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}





