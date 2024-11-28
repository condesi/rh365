<?php
  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/models_persona.php';
    $persona = new Persona();


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

  
    $fechaAsisten = htmlspecialchars($_POST['fechaAsisten'],ENT_QUOTES,'UTF-8');
    $typeShift = htmlspecialchars($_POST['typeShift'],ENT_QUOTES,'UTF-8');
     $idsPersonas = htmlspecialchars($_POST['idsPersonas'],ENT_QUOTES,'UTF-8');

      $fecha_Proxiomo_pago = date('Y-m-d H:i:s',strtotime($fechaAsisten."+ 1 month"));

       $idPersona = explode(",",$idsPersonas);

       for ($i=0; $i <count($idPersona) ; $i++) { 
         if ($idPersona[$i] !='') {

         $consulta = $persona->RegistrarJornadasPersonales($idPersona[$i],$fechaAsisten,$fecha_Proxiomo_pago,$typeShift);
      }
     }

      for ($i=0; $i <count($idPersona) ; $i++) { 
         if ($idPersona[$i] !='') {

         $consulta = $persona->ActualizarOnPersonas($idPersona[$i],$stado="On");
      }
     }

    echo $consulta;

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> '');
    echo json_encode($response);
}

