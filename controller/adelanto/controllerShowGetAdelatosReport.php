<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      //iniciamos la sesion
session_start();

    require '../../models/modelo_adelantos.php';
    $adelanto = new Adelantos();


//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
    echo json_encode($response);
    return;
}


    $idpersona = htmlspecialchars($_POST['idpersona'],ENT_QUOTES,'UTF-8');
    $consulta = $adelanto->listar_Adelantos_Persona($idpersona);
    if($consulta){
        echo json_encode($consulta);
    }else{
        echo '{
        "sEcho": 1,
        "iTotalRecords": "0",
        "iTotalDisplayRecords": "0",
        "aaData": []
    }';
    }
   

        

} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}
