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

$cont=$asistencia->verificar_Asistencia($fechas);
if($cont==0){

 $IdPersona = explode(",",$vectorIdpersonas );


 for ($i=0; $i <count($IdPersona) ; $i++) { 
  if ($IdPersona[$i] !='') {
   $consulta = $asistencia->Registro_Asistencia($IdPersona[$i],$fechas,date('Y'));

 }
}
echo $consulta;
}else{
	 $response = array('status' => true,'auth' => true,'msg' => 'La asistencia para la fecha: '.$fecha.' ya esta registrado, sólo puedes editar.','data'=>100);
    echo json_encode($response);
}
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}



