<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
   setcookie("activo", 1, time() + 3600);


   if (isset($_POST['data'])) {
    $data = $_POST['data'];
    date_default_timezone_set('America/Lima');

    /*$delantos = htmlspecialchars($_POST['delantos'], ENT_QUOTES, 'UTF-8');
    $extras = htmlspecialchars($_POST['extras'], ENT_QUOTES, 'UTF-8');*/
    $delantos = filter_var($_POST['delantos'], FILTER_VALIDATE_BOOLEAN);
$extras = filter_var($_POST['extras'], FILTER_VALIDATE_BOOLEAN);

    $idpersona = htmlspecialchars($_POST['idpersona'], ENT_QUOTES, 'UTF-8');
    require '../../models/modelo_pagosjornada.php';
    $pago = new Pagos;


    // Verificar si los datos tienen contenido y si es un array válido
    if (!empty($data) && is_array($data)) {
        // Iterar los datos
      $maxFecha = null; // Variable para almacenar la fecha mayor
      foreach ($data as $row) {
        if (isset($row['fecha']) && !empty($row['fecha'])) {
          $fecha = $row['fecha'];
          $timestamp = strtotime($fecha);
          if ($maxFecha === null || $timestamp > $maxFecha) {
            $maxFecha = $timestamp;
          }
        } else {

         $response = array('status' => false, 'auth' => true, 'msg' => 'Datos incompletos', 'data' => '');
         echo json_encode($response);
         exit;
       }
     }
     // Convertir la fecha mayor al formato deseado (si es necesario)
     $fechapagado = date('Y-m-d', $maxFecha);
        // Sumar un mes a la fecha mayor
     $fechaproximo = date('Y-m-d', strtotime('+1 month', $maxFecha));

         //Registrar pagos tabal temporal

     foreach ($data as $row) {
           // Verificar si los campos requeridos están presentes y no están vacíos
      if (isset($row['mesSeleccionado']) && isset($row['fecha']) && isset($row['monto']) && !empty($row['mesSeleccionado']) && !empty($row['fecha']) && !empty($row['monto'])) {
                // Obtener los valores de los campos
        $consulta = $pago->PagosMensualesPersonal($row['mesSeleccionado'],$row['fecha'], $row['monto'],$idpersona,date('Y-m-d'));

      }
    }

    $consulta=$pago->ActualizarEstadoJornadaPersonalOn($idpersona,$fechapagado,$fechaproximo);


    if($delantos===true){
      $advancesData =$pago->getAllAdvancesPeople($idpersona);
      if ($advancesData['status'] && !empty($advancesData['data'])) {

        foreach ($advancesData['data'] as $advance) {
          $pago->PagarAdelantosPersonalOn($idpersona,date('Y-m-d'),$advance['Fecharegisto'],$advance['Monto']);
        }
      } else {
         //return json_encode($advancesData);;
      }

    }

    if($extras===true){

       $extraHours =$pago->getAllPeopleExtraHours($idpersona);
      if ($extraHours['status'] && !empty($extraHours['data'])) {

        foreach ($extraHours['data'] as $Hours) {
            $pago->PagarHorasExtrasPersonalOn($idpersona,$Hours['hextra'],$Hours['fecharegistro'],date('Y-m-d'),$Hours['total']);
        }
      } else {
         //return json_encode($extraHours);;
      }

   }

   $response = array('status' => true, 'auth' => true, 'msg' => 'Datos procesados correctamente','data'=>$consulta);
   echo json_encode($response);

 } else {

  $response = array('status' => false, 'auth' => true, 'msg' => 'Datos inválidos', 'data' => '');
  echo json_encode($response);
  return;

}
} else {

  $response = array('status' => false, 'auth' => true, 'msg' => 'No se encontraron datos', 'data' => '');
  echo json_encode($response);
  return;

}


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