<?php 

require_once 'model/model_validations.php';
class Shift
{
    private $conexion;
    private $validations;

    public function __construct()
    {
        require_once 'modelo_conexion.php';
        $this->conexion = new Conexion();
        $this->conexion->conectar();
        $this->validations = new Validations($this->conexion);
    }

    function registerShift($dayName, $dayNumber, $morningEntryTime, $morningExitTime, $type, $afternoonEntryTime, $afternoonExitTime, $status)
    {
        $validationResult = $this->validateHours($morningEntryTime, $morningExitTime, $afternoonEntryTime, $afternoonExitTime,$dayNumber);

        if (!$validationResult['status']) {
            return $validationResult;
        }

        if ($this->validations->isDuplicate('shifts', 'day_name', $dayName, null, 'id')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un turno registrado para este día', 'data' => $dayNumber);
        }

        $sql = "INSERT INTO shifts (day_name, day_number, morning_entry_time, morning_exit_time, type, afternoon_entry_time, afternoon_exit_time, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("sisssssss", $dayName, $dayNumber, $morningEntryTime, $morningExitTime, $type, $afternoonEntryTime, $afternoonExitTime, $status);

        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción' . $stmt->error, 'data' => '');
        }
    }

    function validateHours($morningEntryTime, $morningExitTime, $afternoonEntryTime, $afternoonExitTime,$dayNumber)
    {
        if ($morningEntryTime >= $morningExitTime || $afternoonEntryTime >= $afternoonExitTime || $morningExitTime >= $afternoonEntryTime) {
            return array('status' => false, 'auth' => true, 'msg' => 'Las horas no cumplen con las restricciones', 'number' => $dayNumber);
        }

        return array('status' => true, 'auth' => true, 'msg' => 'Horas válidas', 'data' => '');
    }

    function getShifts()
    {
        $sql = "SELECT id, day_name, day_number, morning_entry_time, morning_exit_time, type, afternoon_entry_time, afternoon_exit_time, status, created_at, updated_at FROM shifts";
        $result = $this->conexion->conexion->query($sql);

        if ($result) {
            $shifts = array();
            while ($row = $result->fetch_assoc()) {
                $shifts[] = $row;
            }

            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $shifts);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos' , 'data' => '');
        }
    }

    function updateShift($id, $dayName, $dayNumber, $morningEntryTime, $morningExitTime, $type, $afternoonEntryTime, $afternoonExitTime, $status)
    {
        $validationResult = $this->validateHours($morningEntryTime, $morningExitTime, $afternoonEntryTime, $afternoonExitTime, $dayNumber);

        if (!$validationResult['status']) {
            return $validationResult;
        }

        if ($this->validations->isDuplicate('shifts', 'day_name', $dayName, $id, 'id')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un turno registrado para este día', 'data' => $dayNumber);
        }

        $sql = "UPDATE shifts SET day_name = ?, day_number = ?, morning_entry_time = ?, morning_exit_time = ?, type = ?, afternoon_entry_time = ?, afternoon_exit_time = ?, status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("sissssssi", $dayName, $dayNumber, $morningEntryTime, $morningExitTime, $type, $afternoonEntryTime, $afternoonExitTime, $status, $id);

        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Actualización exitosa', 'data' => ''.$morningExitTime);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización' . $stmt->error, 'data' => '');
        }
    }
    function removeShift($id)
    {
        $sql = "DELETE FROM shifts WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Eliminación exitosa', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la eliminación' . $stmt->error, 'data' => '');
        }
    }

    function getShiftById($id)
    {
        $sql = "SELECT id, day_name, day_number, morning_entry_time, morning_exit_time, type, afternoon_entry_time, afternoon_exit_time, status, created_at, updated_at FROM shifts WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $shift = $result->fetch_assoc();

            return array('status' => true, 'auth' => true, 'msg' => 'Turno encontrado', 'data' => $shift);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Turno no encontrado' . $stmt->error, 'data' => '');
        }
    }

    function ShowShiftsNormal($normal) {
    $sql = "SELECT id, day_number, day_name, morning_entry_time, morning_exit_time, afternoon_entry_time, afternoon_exit_time FROM shifts WHERE type = ?";
    
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $normal);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $shifts = array();
        while ($row = $result->fetch_assoc()) { $row['day_name'] = $this->translateDayName($row['day_name']);  $shifts[] = $row; }
        return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $shifts);
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos'. $stmt->error, 'data' => '');
    }
}

 function translateDayName($dayName) {
    $translations = array(
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    );

    return isset($translations[$dayName]) ? $translations[$dayName] : $dayName;
}

 function reverseTranslateDayName($dayName) {
      
       
       $translations = array(
        'Lunes' => 'Monday',
        'Martes' => 'Tuesday',
        'Miércoles' => 'Wednesday',
        'Jueves' => 'Thursday',
        'Viernes' => 'Friday',
        'Sábado' => 'Saturday',
        'Domingo' => 'Sunday'
    );

    return isset($translations[$dayName]) ? $translations[$dayName] : $dayName;
    }



}


 ?>