<?php
  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_hextra.php';
     $extra = new Hextras;


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

  if(isset($_POST['valores'])) {
  $valoresJSON = $_POST['valores'];

  date_default_timezone_set('America/Lima');
   $fechaActual = date('Y-m-d');
    $year = date('Y');

  // Decodificar la cadena JSON en un array utilizando json_decode
  $valores = json_decode($valoresJSON, true);

  // Verificar si la decodificación fue exitosa
  if($valores !== null) {
    // Iterar sobre los valores utilizando el bucle foreach
    foreach($valores as $valor) {


      $consulta = $extra->Registrar_Horas_Extras($valor['idpesona'],$valor['cantidad'],$valor['total'],$fechaActual,$year);
    }

    // Enviar una respuesta de éxito al cliente
    echo $consulta;
  } else {
    // Enviar una respuesta de error al cliente si la decodificación falla
  

     $response = array('status' => true,'auth' => true,'msg' => 'Error al decodificar los valores JSON','data'=> '');
    echo json_encode($response);
  }
} else {
  // Enviar una respuesta de error al cliente si no se recibieron los valores
  
    $response = array('status' => true,'auth' => true,'msg' => 'No se recibieron los valores correctamente','data'=> '');
    echo json_encode($response);
} 
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}

?>