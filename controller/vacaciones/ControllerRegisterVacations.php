<?php
  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_vacaciones.php';
    $vacations = new Vacaciones;


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    return;
}

     $idempleado = htmlspecialchars($_POST['idempleado'],ENT_QUOTES,'UTF-8');
     $diadisponibleactual = htmlspecialchars($_POST['diadisponibleactual'],ENT_QUOTES,'UTF-8');
     $fechainicio = htmlspecialchars($_POST['fechainicio'],ENT_QUOTES,'UTF-8');
     $fechafinal = htmlspecialchars($_POST['fechafinal'],ENT_QUOTES,'UTF-8');
     $descrition = htmlspecialchars($_POST['descrition'],ENT_QUOTES,'UTF-8');
     $motivo = htmlspecialchars($_POST['motivo'],ENT_QUOTES,'UTF-8');
      $diasselecionados = htmlspecialchars($_POST['diasselecionados'],ENT_QUOTES,'UTF-8');
    

    $consulta = $vacations->Registrar_Vacaciones_Empleado($idempleado,$diadisponibleactual,$fechainicio,$fechafinal,$descrition,$motivo,$diasselecionados);
        echo $consulta;
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> '');
    echo json_encode($response);
}


