<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //iniciamos la sesion
session_start();
    require '../../models/modelo_pagosjornada.php';
     $pago = new Pagos;
//esta pregunta la debe hacer en todos los archivos para validar que antes el usuario haya iniciado sesion
if (isset($_SESSION['username'])) {
    setcookie("activo", 1, time() + 3600);
} else {
    
    $response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
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


           $consulta = $pago->Filtrar_Reportes_Pagosjornada($fechaInicio,$fechaFin);
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
  // Puedes manejar el caso de error o mostrar un mensaje al usuario//
 $consulta = $pago->Listar_report_Jornada();
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
    
    $response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=> $consulta);
    echo json_encode($response);
}
?>


