<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

      try {
     
        $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
        $type_id = isset($_POST['type_id']) ? htmlspecialchars($_POST['type_id']) : null;
        $startDate = isset($_POST['startDate']) ? htmlspecialchars($_POST['startDate']) : null;
        $endDate = isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate']) : null;
        $startTime = isset($_POST['startTime']) ? htmlspecialchars($_POST['startTime']) : null;
        $endTime = isset($_POST['endTime']) ? htmlspecialchars($_POST['endTime']) : null;
        $id_persona = htmlspecialchars($_POST['id_persona'],ENT_QUOTES,'UTF-8');
        $dayConsumess = isset($_POST['dayConsumess']) ? htmlspecialchars($_POST['dayConsumess']) : 0;
        $currentPermis = isset($_POST['currentPermis']) ? htmlspecialchars($_POST['currentPermis']) : 0;
        $number_day = isset($_POST['number_day']) ? htmlspecialchars($_POST['number_day']) : 0;
         
        $daysPermission = isset($_POST['daysPermission']) ? htmlspecialchars($_POST['daysPermission']) : 0;
        $hoursPermission = isset($_POST['hoursPermission']) ? htmlspecialchars($_POST['hoursPermission']) : null;
        $number_corespondi = isset($_POST['number_corespondi']) ? htmlspecialchars($_POST['number_corespondi']) : null;
        $available_days = isset($_POST['remainingDays']) ? htmlspecialchars($_POST['remainingDays']) : 0;
        $remainingHours = isset($_POST['json_remainingHours']) ? json_decode($_POST['json_remainingHours'], true) : null;

         $isDeletPhoto = isset($_POST['isDeletPhoto']) ? filter_var($_POST['isDeletPhoto'], FILTER_VALIDATE_BOOLEAN) : false;
        if(empty($type_id)) throw new Exception('Deves seleccionar tipo de permiso.');
        $description='';
         $status=1;
         $day_number=0;
         date_default_timezone_set('America/Lima');
        $dateCurrent = new DateTime('now');
        $dateOnly = $dateCurrent->format('Y-m-d');
        $current_year= $dateCurrent->format('Y');
        $hoursOnly = $dateCurrent->format('H:i:s');
      
           
       
        require '../../models/model_permission.php';
        $permission = new Permission();
        //variables
        $isDateAndTime=false;


       if (!empty($startDate) && !empty($endDate)) {
                $startDateTimestamp = strtotime($startDate);
                $endDateTimestamp = strtotime($endDate);
                $currentTimestamp = strtotime($dateOnly);

                //validar escala de fechas
                if ($startDateTimestamp >= $endDateTimestamp)  throw new Exception('La fecha de inicio debe ser anterior a la fecha de finalización.');
                //validar ambas fechas deben ser mayor o igual al actual
                 if ($startDateTimestamp < $currentTimestamp || $endDateTimestamp < $currentTimestamp) {
                    if(empty($id)) throw new Exception('Los permisos deben ser desde '.$dateOnly.' hacia el futuro.');
                 }
                
                 $isDateAndTime = true;
                 //obtener fecha para poder si aun se puede editar
                 if(!empty($id)){
                    $datePermsActive  = $permission->ActiveCurrenDate($id,$current_year);
                    //para restar su permiso ya que al actualizar ya esta sumando 
                    if(!empty($datePermsActive) ) $resul= $datePermsActive['quantity_day'];
                    //verifiacr si estamos en rango permitido de edicion
                    if(!empty($datePermsActive)  && strtotime($datePermsActive['end_date']) >= strtotime($dateOnly)){
                        $available_days =   ($number_day) - $daysPermission;
                        $daysPermission= $daysPermission- $resul;
                    }else{
                        throw new Exception('No puedes editar este permiso debido a que la fecha para la edicion ya a finalizado. Agregar nuevo.');
                    }
                  }
                 //verifica si esta forzando cambio de permiso al editar
                  if(!empty($id) && $currentPermis != $type_id) throw new Exception('En edicion solo esta permitido cambio de fecha no cambio de tipo de permio.'); 
                   
                //verificar si ya tiene un permiso activo para este tipo
                $exists = $permission->checkPesmisTypeExists($id_persona, $type_id,$current_year);
                if(!empty($exists) && $exists['type_id'] == $type_id){

                   if(strtotime($exists['end_date']) >= strtotime($dateOnly)){
                      //si estamos editando debe dejar pasar
                      if(empty($id))  throw new Exception('Para este tipo de permiso tienes activo hasta '.$exists['end_date'] .' Espere que culmine'); 
                      
                   }else{
                    //entonces mandarle a actualizar
                    $available_days = $number_corespondi - ($daysPermission + $exists['quantity_day']);
                    $id=  $exists['id'];
                    //verificar dias disponible
                     if($available_days <0 ) throw new Exception('No te quedan disponibilidad de dias  para este tio de permiso para este año. tus dias restantes son: '.$exists['available_days']);
                   }
                   

                }else{
                    //entonces en un permiso nuevo
                   
                }
               
            }
         else if(!empty($startDate) && !empty($endDate)) throw new Exception('Ambas fechas son obligatorias.');
        
        if (!empty($startTime) && !empty($endTime)) {
            $startTimeTimestamp = strtotime($startTime);
            $endTimeTimestamp = strtotime($endTime);

            if ($startTimeTimestamp >= $endTimeTimestamp) 
                throw new Exception('La hora de inicio debe ser anterior a la hora de finalización.');
            

            $isDateAndTime = true;
             //obtener fecha para poder si aun se puede editar
             if(!empty($id)){
            
                 // Agregar ':00' al final de endTime si es necesario
                $endTime .= ':00';
                // Crear objetos DateTime para startTime y endTime
                $startTimeObj = DateTime::createFromFormat('H:i:s', $startTime);
                $endTimeObj = DateTime::createFromFormat('H:i:s', $endTime);
                // Verificar si la conversión fue exitosa
                if (!$startTimeObj || !$endTimeObj) {
                    echo json_encode(array('status' => false, 'msg' => 'Error al convertir las horas en objetos DateTime'));
                    exit;
                }
                // Validar si startTime es anterior a endTime
                if ($startTimeObj >= $endTimeObj) {
                    echo json_encode(array('status' => false, 'msg' => 'La hora de inicio debe ser anterior a la hora de fin'));
                    exit;
                }
                // Calcular la diferencia de tiempo
                $diff = $startTimeObj->diff($endTimeObj);
                // Formatear la diferencia de tiempo en horas y minutos
                $horasARestar = $diff->format('%H:%I:%S');
                // Convertir las horas a un objeto DateTime para calcular los días a restar
                $horasARestarObj = DateTime::createFromFormat('H:i:s', $horasARestar);
                // Calcular los días a restar
                $intervaloHoras = $startTimeObj->diff($horasARestarObj);
                $diasARestar = $intervaloHoras->format('%a');
                // Calcular los días restantes
                $diasRestantes = $number_day - $diasARestar;
                // Imprimir resultados
               // echo "La diferencia entre $startTime y $endTime es: $horasARestar<br>";
               // echo "Después de restar $horasARestar horas, quedan $diasRestantes días disponibles.";

               $hoursPermission =$horasARestar;
                // Convertir el tiempo restado a minutos
                list($horas, $minutos, $segundos) = explode(':', $horasARestar);
                $totalMinutosARestar = $horas * 60 + $minutos;
                // Calcular los minutos restantes en los días disponibles
                $totalMinutosDisponibles = $number_day * 24 * 60;
                $totalMinutosRestantes = $totalMinutosDisponibles - $totalMinutosARestar;
                // Manejar el caso en que los minutos restantes sean negativos
                if ($totalMinutosRestantes < 0) {
                    // Si los minutos restantes son negativos, establecerlos en cero
                    $totalMinutosRestantes = 0;
                }
                // Calcular los días, horas y minutos restantes
                $diasRestantes = floor($totalMinutosRestantes / (24 * 60));
                $horasRestantes = floor(($totalMinutosRestantes % (24 * 60)) / 60);
                $minutosRestantes = $totalMinutosRestantes % 60;
                // Imprimir resultados
                #echo "Después de restar $horasARestar horas, quedan $diasRestantes días, $horasRestantes horas y $minutosRestantes minutos disponibles.";
                $available_times = $diasRestantes.'/ '.$horasRestantes.':'.$minutosRestantes;
             }else{
                 
                //aqui entra si es nuevo

                //chequear si ya existe eun permiso para este ttipo
                $exists = $permission->checkPesmisTypeExists($id_persona, $type_id,$current_year);
                if(!empty($exists) && $exists['type_id'] == $type_id){

                    if(!strtotime($exists['end_time']) >= strtotime($hoursOnly)){
                       //si estamos editando debe dejar pasar
                     // if(empty($id))  throw new Exception('Para este tipo de permiso tienes activo hasta '.$exists['end_time'] .' Espere que culmine'); 
                       
                    }else{
                        $available_times = $exists['available_times'];// 14/ 23:30:00
                        list($fecha, $hora) = explode(' ', $available_times);

                        $horasConsumes =  $exists['quantity_hours'];//'00:30:00'
                        $diadDisponibles = explode('/', $fecha)[0];//14
                        $horasDisponibles = date('H:i:s', strtotime($hora));//23:30:00
                        $hoursPermission;//00:10:00

                        // Convertir horas a segundos
                            $horasConsumesSegundos = strtotime($horasConsumes) - strtotime('00:00:00');
                            $horasDisponiblesSegundos = strtotime($horasDisponibles) - strtotime('00:00:00');
                            $hoursPermissionSegundos = strtotime($hoursPermission) - strtotime('00:00:00');

                            // Calcular horas y días disponibles después de dar permiso
                            $horasRestantesSegundos = $horasDisponiblesSegundos - $hoursPermissionSegundos;
                            $horasRestantes = gmdate('H:i:s', $horasRestantesSegundos);

                            // Si las horas restantes son negativas, establecerlas en 00:00:00
                            if ($horasRestantesSegundos < 0) {
                                $horasRestantes = '00:00:00';
                            }

                            // Los días disponibles no cambian, ya que no se ven afectados por las horas concedidas
                            $diasRestantes = $diadDisponibles;

                            // Mostrar resultados
                           # echo "Horas disponibles después del permiso: $horasRestantes<br>";
                           # echo "Días disponibles después del permiso: $diasRestantes";

                               // Convertir horas consumidas a segundos
                                $horasConsumesSegundos = strtotime($horasConsumes) - strtotime('00:00:00');
                                // Convertir horas de permiso a segundos
                                $hoursPermissionSegundos = strtotime($hoursPermission) - strtotime('00:00:00');
                                // Sumar las horas de permiso a las horas consumidas
                                $totalSegundos = $horasConsumesSegundos + $hoursPermissionSegundos;

                                // Convertir el total de segundos a formato de tiempo legible
                                $totalHoras = gmdate('H:i:s', $totalSegundos);
                                // Mostrar resultados
                                #echo "Total de horas después de sumar las horas de permiso: $totalHoras";
                                $hoursPermission=$totalHoras;
                                $available_times =$diasRestantes.'/ '.$horasRestantes;
                                $id=  $exists['id'];
                                if($diasRestantes <0 ) throw new Exception('No te quedan disponibilidad de dias  para este tio de permiso para este año. tus dias restantes son: '.$diasRestantes);
                                if($horasRestantes == '00:00:00') throw new Exception('No te quedan disponibilidad de horas  para este tio de permiso para este año. tus horas restantes son: '.$horasRestantes);
                    }

                }else{
                    $available_times = $remainingHours['day'].'/ '.$remainingHours['hours'];
                }
               
             }
           
           

               $startDate =  null;
               $endDate =  null;
               $daysPermission=0;
        } else  if(!empty($startTime) && !empty($endTime)) throw new Exception('Ambas horas son obligatorias.');
        
        if(!$isDateAndTime) throw new Exception('Deves seleccionar fecha o horas para los permisos.');
        if(empty($id_persona)) throw new Exception('Deves seleccionar un personal para asignar permisos.');

      

          // Manejar archivo
        if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
            require '../base/ControllerManagementFiles.php';
            try {
                $fileName = uploadPhotoAll("photo", $id_persona,'certification');
            } catch (Exception $e) {
      
                echo json_encode(array('status' => false, 'msg' => 'Error al subir la foto: ' . $e->getMessage()));
                exit();
            }
        }
        $photo = (isset($fileName) && $fileName) ? $fileName : '';
        $available_times = isset($available_times) ? $available_times : null;
    
        if(empty($id)){
          $response = $permission->Register_permission($id_persona, $type_id, $startDate, $endDate, $startTime, $endTime, $daysPermission, $hoursPermission, $available_days, $available_times, $status, $day_number, $remainingHours, $description, $photo,$dateCurrent->format('Y'));
        }else{
         $response = $permission->updatePermission($id, $id_persona, $type_id, $startDate, $endDate, $startTime, $endTime, $daysPermission, $hoursPermission, $available_days, $available_times, $status, $day_number, $remainingHours, $description, $photo,$dateCurrent->format('Y'));    
        }

     

           echo json_encode($response);
      
      } catch (Exception $e) {
            $response = array('status' => false, 'auth' => false, 'msg' => $e->getMessage(), 'data' => '', 'tipo' => 'alert-danger');
            
            echo json_encode($response);
        }

    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        http_response_code(403);
        echo json_encode($response);
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}

 ?>