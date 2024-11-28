<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $code = htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');
        $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
        $iduser = htmlspecialchars($_POST['id_user'], ENT_QUOTES, 'UTF-8');
        $attendance = isset($_POST['attendance']) ? htmlspecialchars($_POST['attendance'], ENT_QUOTES, 'UTF-8'): '';
        $location = isset($_POST['location']) ? json_decode($_POST['location'], true) : null;
       
        

        if(empty($iduser)) throw new Exception("El usuario es requerrido", 1);
        if(empty($type)) throw new Exception("El tipo es requerrido", 1);
        if(empty($code)) throw new Exception("El código es requerrido", 1);
        if (empty($location)) throw new Exception("Puntos de geolocalización no identificados", 1);

        $lat = $location['lat'];
        $lng = $location['lng'];
        $accuracy = $location['accuracy'];
          //fehas
        date_default_timezone_set('America/Lima');
        $dateCurrent = new DateTime('now');
        $dayNumber = $dateCurrent->format('N');
        $dateOnly = $dateCurrent->format('Y-m-d');
        $timeEntry = $dateCurrent->format('Y-m-d H:i:s');
        require '../../models/model_entry.php';
        $entry = new Entry();
         //VERIFIAR SI ES ENTRADA O SALIDA
         if(isset($attendance) && $attendance=='entry'){
            $response = $entry->Register_Entry($code, $type,$iduser ,$lat,$lng);
         }else{

                $messenger='';
                $type='';

               //SALIDA
               //Obtener la asistencia del usuario
                $asisted = $entry->getUserAssistedByIdAndByDateCurrente($iduser);
                if ($asisted == false)  throw new Exception("No as ingresado pasa marcar la salida", 1); 
                //Verificar si esta marcando de trade o mañana
                if($asisted['name_shift']==='T_MORNING') $name_shift='T_MORNING';
                if ($asisted['name_shift']==='T_AFTERNOON')   $name_shift='T_AFTERNOON';

                //refificar de esa asistencia falta marcar la salida
                $exit_missing = $entry->checkExitMissingById( $asisted['id']);
                //La salida está pendiente de marcar
                if ($exit_missing === true) {
                       $result = $entry->updateEntryBackground($asisted['user_id'], $dateOnly,  $dateCurrent->format('H:i:s'), $name_shift,$lat,$lng);
                        if ($result !== true){
                            echo json_encode(array('status' => true, 'auth' => true, 'msg' => 'Error en la actualización: '.$name_shift));
                            exit();
                        }
                        $messenger='La salida se registro corectamente :'.$dateCurrent->format('H:i:s') ; 
                        $type='warning';
                } elseif ($exit_missing === false) {
                // "La salida ya está marcada por el sistema automaticamente .pero actualizamos manualmente";
                       
                       # $result = $entry->updateEntryBackground($asisted['user_id'], $dateOnly,  $dateCurrent->format('H:i:s'), $name_shift);
                       # if ($result !== true){
                          #  echo json_encode(array('status' => true, 'auth' => true, 'msg' => 'Error en la actualización: '.$name_shift));
                          #  exit();
                       # }
                       # $type='info';
                       # $messenger='La salida ya ha abia sido marcado por el sistema, pero se actualizo con la hora'.$dateCurrent->format('H:i:s'). ' correctamente' ;

                       $response = array('status' => false, 'auth' => false, 'msg' => 'La salida ya ha sido marcado.', 'data' => '', 'tipo' => 'alert-danger');
                       echo json_encode($response);
                        exit();

                } else {
                    $messenger= "Error al verificar la salida.";
                }

               $response= array('status' => $result, 'auth' => $result, 'msg' => $messenger, 'data' => '', 'tipo' => $type);
            }

          $response['data'] = null;
        
          $response['time'] = $timeEntry;
          echo json_encode($response);

        
    } catch (Exception $e) {
        $response = array('status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage(), 'data' => '', 'tipo' => 'alert-danger');
        echo json_encode($response);
    }
} else {
    $response = array('status' => false, 'auth' => false, 'msg' => 'SOLO SE PUEDE POST.', 'data' => '', 'tipo' => 'alert-danger');
    echo json_encode($response);
}
?>