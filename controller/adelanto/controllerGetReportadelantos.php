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
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '','ultimoID'=>0);
    echo json_encode($response);
    return;
}

 $fechaInicio = htmlspecialchars($_POST['fechainicio'], ENT_QUOTES, 'UTF-8');
 $fechaFin = htmlspecialchars($_POST['fechafin'], ENT_QUOTES, 'UTF-8');
if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
  // Ambas variables están definidas y contienen contenido
  // Puedes realizar aquí la lógica que necesites

      date_default_timezone_set('America/Lima');
          $fechaInicio = date($fechaInicio );
          $fechaFin = date($fechaFin);


           $consulta = $adelanto->Filtrar_Reportes_adelantos($fechaInicio,$fechaFin);
        if ($consulta) {
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
  // Al menos una de las variables está vacía o no está definida
  // Puedes manejar el caso de error o mostrar un mensaje al usuario
 $consulta = $adelanto->Listar_report_adelantos();
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

}  







} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> '','ultimoID'=>0);
    echo json_encode($response);
}
