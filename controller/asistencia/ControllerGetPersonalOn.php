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

    if (isset($_POST['date'])) {
        $fecha = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
   // El código para procesar la variable $date aquí
        date_default_timezone_set('America/Lima');
        $date= date($fecha); 

        $consulta = $asistencia->listar_Personas_Asistencia_Show_On($date);
        if ($consulta) {
            $personal = $asistencia->listar_Personas_Asistencia_Jornadas();
            if ($personal) {
                $consultaIds = array_column($consulta['data'], 'idpersona');
                foreach ($personal['data'] as $key => $value) {
                    if (!in_array($value['idpersona'], $consultaIds)) {
                        $consulta['data'][] = $value;
                    }
                }
            }

            echo json_encode($consulta);

        } else{
            echo '{
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }

    } else {
        $consulta = $asistencia->listar_Personas_Asistencia_Jornadas();
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




