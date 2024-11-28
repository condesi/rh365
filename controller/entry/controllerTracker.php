<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $code = htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');
        $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
        $attendance = isset($_POST['attendance']) ? htmlspecialchars($_POST['attendance'], ENT_QUOTES, 'UTF-8'): '';
        $lat = null;
        $lng = null;
        $accuracy = null;

        if(empty($code) || empty($type)){
            echo json_encode(array('status' => false, 'auth' => false, 'msg' => 'Igrese su codigo.', 'data' => '', 'tipo' => 'alert-danger'));
            exit();
        }

        require '../base/Controller_data.php';

        if (trim($code) !== clean_input_characters($code)) {
            throw new Exception('Bad Request');
        }

        require '../../models/model_entry.php';
        $entry = new Entry();

        $isExisteCode = $entry->verificationCode($code);
        
        if (!$isExisteCode) {
            echo json_encode(array('status' => false, 'auth' => false, 'msg' => 'El código ingresado no existe.', 'data' => '', 'tipo' => 'alert-danger'));
            exit();
        }

     
         $user = $entry->getUserByCode($code);

        if (!$user['status'] || !$user['auth']) {
            echo json_encode(array('status' => false, 'auth' => false, 'msg' => 'Usuario con ese código no existe.', 'data' => '', 'tipo' => 'alert-danger'));
            exit();
        }

        $userData = $user['data'];

        // Verifica si el usuario tiene el campo "id" definido
        if (!isset($userData['iduser'])) {
            // Maneja el caso en que el campo "id" no está definido en los datos del usuario
            echo json_encode(array('status' => false, 'auth' => true, 'msg' => 'Error: Campo "iduser" no definido en los datos del usuario.', 'data' => '', 'tipo' => 'alert-danger'));
            exit();
        }

          //fehas
         date_default_timezone_set('America/Lima');
        $dateCurrent = new DateTime('now');
        $dayNumber = $dateCurrent->format('N');
        $dateOnly = $dateCurrent->format('Y-m-d');
        $timeEntry = $dateCurrent->format('Y-m-d H:i:s');
         //VERIFIAR SI ES ENTRADA O SALIDA
         if(isset($attendance) && $attendance=='entry'){
            $response = $entry->Register_Entry($code, $type, $userData['iduser'] ,$lat,$lng);
         }else{

                $messenger='';
                $type='';

               //SALIDA
               //Obtener la asistencia del usuario
                $asisted = $entry->getUserAssistedByIdAndByDateCurrente($userData['iduser']);
                if ($asisted == false) {
                    echo json_encode(array('status' => false, 'auth' => false, 'msg' => 'No as ingresado pasa marcar la salida', 'data' => '', 'tipo' => 'alert-danger'));
                    exit();
                }
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

          $response['user'] = $userData;
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

