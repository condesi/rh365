<?php
  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_asistencia.php';
    $asistencia = new Asistensia();


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

$vectorIdpersonas = htmlspecialchars($_POST['idpersonasselect'],ENT_QUOTES,'UTF-8');
$fecha = htmlspecialchars($_POST['fecha'],ENT_QUOTES,'UTF-8');
date_default_timezone_set('America/Lima');
 $fechas= date($fecha); 

$resettable  =  $asistencia->Resetear_Asistencia_date($fechas,date('Y'));

if($resettable==1){

   $IdPersona = explode(",",$vectorIdpersonas );
 for ($i=0; $i <count($IdPersona) ; $i++) { 
  if ($IdPersona[$i] !='') {
   $consulta = $asistencia->Registro_Asistencia($IdPersona[$i],$fechas,date('Y'));

 }
}

echo $consulta;
}else{
$response = array('status' => true,'auth' => true,'msg' => 'No se pudo resetear la asistencia para actualizar ','data'=>0);
    echo json_encode($response);

}

   

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=>'');
    echo json_encode($response);
}



