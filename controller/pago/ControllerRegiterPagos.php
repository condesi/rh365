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
//////////

if(isset($_POST['rowselectd'])) {
  $valoresJSON = $_POST['rowselectd'];

  $idpersona = htmlspecialchars($_POST['idpersona'],ENT_QUOTES,'UTF-8');

  date_default_timezone_set('America/Lima');
   $fechaActual = date('Y-m-d');
 
  // Decodificar la cadena JSON en un array utilizando json_decode
  $valores = json_decode($valoresJSON, true);

 // Verificar si la decodificación fue exitosa
  if($valores !== null) {
    // Iterar sobre los valores utilizando el bucle foreach
    foreach($valores as $valor) {

      $consulta = $extra->Registrar_Pagos_Horas_Extras($valor['idextra'],$valor['fecha'],$valor['horastotal'],$valor['montototal'],$idpersona,$fechaActual);
    }

    if($consulta>=1){
       foreach($valores as $valor) {
         $resquest = $extra->DarDeBajasHorasExtras($valor['idextra'],$idpersona);
       }
    }


    // Enviar una respuesta de éxito al cliente
    echo $resquest;
  } else {
    // Enviar una respuesta de error al cliente si la decodificación falla
    echo "Error al decodificar los valores JSON";
  }

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}


}

?>