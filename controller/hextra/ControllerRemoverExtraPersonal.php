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

  
  $idempleado = htmlspecialchars($_POST['idempleado'],ENT_QUOTES,'UTF-8');
   $idxestra = htmlspecialchars($_POST['idxestra'],ENT_QUOTES,'UTF-8');
      
     $consulta = $extra->Remover_Personal_Hextra($idempleado,$idxestra);
 
       
    echo $consulta;
    
    

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> '');
    echo json_encode($response);
}

