<?php

require_once 'model/model_validations.php';

class Permission
{
    private $conexion;
    private $validations;

    public function __construct()
    {
        require_once 'modelo_conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
        $this->validations = new Validations($this->conexion);
    }

   

    public function getpermission($date_init, $date_end, $search){
         $start_time = microtime(true);
        $sql = "SELECT id, name, description,number_day,status, created_at, updated_at FROM type_permission where status=1"; 

        if (!empty($date_end) && !empty($date_init)) {
          $sql .= " WHERE (type_permission.created_at BETWEEN '$date_init 00:00:00' AND '$date_end 23:59:59')";
        }
         // Agregar orden predeterminado y límite solo cuando no hay filtro de fecha
        if (empty($date_end) || empty($date_init)) {
           $sql .= " ORDER BY type_permission.created_at DESC LIMIT 10";
        }

        try {
            $result = $this->conexion->conexion->query($sql);
             $end_time = microtime(true);
              $query_time = round($end_time - $start_time, 3);
            if ($result) {
                
                $category = array();
                while ($row = $result->fetch_assoc()) {
                    $category[] = $row;
                }

                return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $category,'consultation_time' => $query_time);
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos' . $this->conexion->conexion->error, 'data' => '','consultation_time' => $query_time);
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }

    public function Update_Type_Permission($id, $name, $day, $description, $photo, $status){
       try {

        if ($this->validations->isDuplicate('type_permission', 'name', $name, $id, 'id')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Tipo con ese nombre ya exists');
        }
        $sql = "UPDATE type_permission SET name = ?, description = ?, number_day = ?, status = ?, photo = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("sssssi", $name,$description,$day,$status,$photo,  $id);

        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Category updated successfully', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error updating Tipo' . $stmt->error, 'data' => '');
        }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }

    }

 public function getPeopleOn($state){
   

   $sql = "SELECT p.idpersona, p.Nombre, p.Apellidos, p.typePeople, p.Sexo, p.Direccion, p.permission as isPesmis,
       
         IFNULL(CONCAT(pe.start_date, ' ~ ', pe.end_date), CONCAT(pe.start_time, ' ~ ', pe.end_time)) AS period,
         IF(pe.quantity_day IS NOT NULL AND pe.quantity_day != 0, pe.quantity_day, pe.quantity_hours) AS quantity,

       
         IF(pe.available_days IS NOT NULL AND pe.available_days != 0, pe.available_days, pe.available_times) AS availability,

        pe.id as id_permission, pe.status as isactive,
        tp.name as name_permiss, tp.number_day,tp.id as id_tp_per
        FROM personas p
        LEFT JOIN permissions pe ON p.idpersona = pe.id_people
        LEFT JOIN type_permission tp ON pe.type_id = tp.id
        WHERE p.statedinicio = '$state' ";


    $arreglo = array();
    if ($consulta = $this->conexion->conexion->query($sql)) {
      while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

        $arreglo['data'][]=$consulta_VU;

      }
      return $arreglo;
      $this->conexion->cerrar();
    }

  }

  function getPermiByidPeopleAndByIdPermission($idpersona,$idpermission){
   $sql="SELECT p.idpersona, p.Nombre, p.Apellidos, p.typePeople, p.Sexo, p.Direccion, p.permission as permis_active,
       
         IFNULL(CONCAT(pe.start_date, ' ~ ', pe.end_date), CONCAT(pe.start_time, ' ~ ', pe.end_time)) AS period,
          IF(pe.quantity_day IS NOT NULL AND pe.quantity_day != 0, pe.quantity_day, pe.quantity_hours) AS quantity,

       
         IF(pe.available_days IS NOT NULL AND pe.available_days != 0, pe.available_days, pe.available_times) AS availability,
        
        pe.id as id_permission , pe.photo,
        tp.name as name_permiss, tp.number_day ,pe.type_id
        FROM personas p
        LEFT JOIN permissions pe ON p.idpersona = pe.id_people
        LEFT JOIN type_permission tp ON pe.type_id = tp.id
        WHERE p.statedinicio = 'on'  and pe.id_people =? and pe.id=? ";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("ii", $idpersona,$idpermission);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $permission = $result->fetch_assoc();
            return array('status' => true, 'auth' => true, 'msg' => 'Categoría encontrada', 'data' => $permission);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Categoría no encontrada' . $stmt->error, 'data' => '');
        }

}

public function photoCurrent($id_permis)
{
    $sql = "SELECT photo FROM permissions WHERE id = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("i", $id_permis);
    if ($stmt->execute() && ($user = $stmt->get_result()->fetch_assoc())) {
        return $user['photo'];
    }
    return null;
}

  public function ShowType($id)
    {
        $sql = "SELECT id, name, description,number_day,status, created_at, updated_at FROM type_permission WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $category = $result->fetch_assoc();
            return array('status' => true, 'auth' => true, 'msg' => 'Categoría encontrada', 'data' => $category);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Categoría no encontrada' . $stmt->error, 'data' => '');
        }
    }

    


    function Register_permission($id_people, $type_id, $start_date, $end_date, $start_time, $end_time, $quantity_day, $quantity_hours, $available_days, $available_times, $status, $day_number, $time_number, $description, $photo,$currencyYear){
    try {
        $id = null;
        $quantity_hours= $quantity_hours=="" ? null  :$quantity_hours;    
        $start_date = empty($start_date) ? null : $start_date;
        $end_date = empty($end_date) ? null : $end_date;
        $start_time = empty($start_time) ? null : $start_time;
        $end_time = empty($end_time) ? null : $end_time;


         $time_number = '';
        
        // Verificar si ya existe un permiso activo para la persona y el tipo de permiso
            if ($this->existePermisoActivo($id_people, $type_id,$currencyYear)) {
                return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un permiso activo para esta persona y tipo de permiso.');
            }

        // Sentencia SQL para la inserción
        $sql = "INSERT INTO permissions (id_people, type_id, start_date, end_date, start_time, end_time, quantity_day, quantity_hours, available_days, available_times, status, day_number, time_number, description, photo,current_year, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->conexion->conexion->prepare($sql);

        // Bind de parámetros
        $stmt->bind_param("iissssisssssssss", $id_people, $type_id, $start_date, $end_date, $start_time, $end_time, $quantity_day, $quantity_hours, $available_days, $available_times, $status, $day_number, $time_number, $description, $photo,$currencyYear);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción: ' . $stmt->error, 'data' => '');
        }
    } catch (Exception $e) {
        return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => ''.$start_date);
    }
}


 public function existePermisoActivo($id_people, $type_id,$currencyYear) {
        $sql = "SELECT COUNT(*) AS count FROM permissions WHERE id_people = ? AND type_id = ? AND end_date > NOW()";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_people, $type_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0; // Devuelve true si hay al menos un permiso activo
    }

 public function existePermisoActivoHora1($id_people, $type_id, $end_time) {
        $sql = "SELECT COUNT(*) AS count FROM permissions WHERE id_people = ? AND type_id = ? AND end_date = CURDATE() AND end_time > NOW()";
         $sql = "SELECT COUNT(*) AS count FROM permissions WHERE id_people = ? AND type_id = ? AND end_time > ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("is", $id_people, $type_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0; // Devuelve true si hay al menos un permiso activo
    }

    // Función para verificar si ya existe un permiso activo basado en la hora
public function existePermisoActivoHora($id_people, $type_id, $end_time) {
    $sql = "SELECT COUNT(*) AS count FROM permissions WHERE id_people = ? AND type_id = ? AND CAST(CONCAT(CURDATE(), ' ', end_time) AS DATETIME) > NOW()";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("is", $id_people, $type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0; // Devuelve true si hay al menos un permiso activo
}

public function ActiveCurrenDate($id_permis,$current_year)
    {
        $sql = "SELECT end_date ,quantity_day,end_time,quantity_hours FROM permissions WHERE id = ? and current_year=? and status= 1";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("is", $id_permis,$current_year);

        if ($stmt->execute() && ($data = $stmt->get_result()->fetch_assoc())) {
            return $data;
        }
        return null;
    }

public function gerCurrenteTypePermission($id_people, $type_id,$current_year)
    {
        $sql = "SELECT id, id_people, type_id, status, current_year FROM permissions WHERE id_people = ? and type_id= ? and current_year=?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("sss", $id_people, $type_id,$current_year);

        if ($stmt->execute() && ($sale = $stmt->get_result()->fetch_assoc())) {
            return $sale['id'];
        }
        return null;
    }

    public function checkPesmisTypeExists($id_people, $type_id,$current_year)
    {
        $sql = "SELECT id, id_people, end_date,end_time,quantity_hours,available_times, type_id, status,available_days, current_year,quantity_day FROM permissions WHERE id_people = ? and type_id= ? and current_year=?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("iii", $id_people, $type_id,$current_year);

        if ($stmt->execute() && ($data = $stmt->get_result()->fetch_assoc())) {
            return $data;
        }
        return null;

    }




    public function getPermissions($start_date, $end_date, $user_id)
    {
        try {
            $sql = "SELECT * FROM permissions WHERE 1";

            if (!empty($start_date) && !empty($end_date)) {
                $sql .= " AND start_date BETWEEN '$start_date' AND '$end_date'";
            }

            if (!empty($user_id)) {
                $sql .= " AND user_id = $user_id";
            }

            $result = $this->conexion->conexion->query($sql);

            if ($result) {
                $permissions = array();
                while ($row = $result->fetch_assoc()) {
                    $permissions[] = $row;
                }

                return array('status' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $permissions);
            } else {
                return array('status' => false, 'msg' => 'Error al recuperar los datos: ' . $this->conexion->conexion->error);
            }
        } catch (Exception $e) {
            return array('status' => false, 'msg' => 'Excepción: ' . $e->getMessage());
        }
    }

    public function updatePermission($id, $id_people, $type_id, $start_date, $end_date, $start_time, $end_time, $quantity_day, $quantity_hours, $available_days, $available_times, $status, $day_number, $time_number, $description, $photo,$currencyYear)
    {
        try {
            $time_number = '';
            $sql = "UPDATE permissions SET id_people = ?, type_id = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ?, quantity_day= quantity_day + ?,quantity_hours=?, available_days = ?, available_times = ?, status = ?, day_number = ?,time_number=?, description = ?, photo = ?, updated_at = NOW() WHERE id = ?";

            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("ssssssissssssssi", $id_people, $type_id, $start_date, $end_date, $start_time, $end_time, $quantity_day, $quantity_hours, $available_days, $available_times, $status, $day_number, $time_number, $description, $photo,$id);

            if ($stmt->execute()) {
                return array('status' => true, 'msg' => 'Actualización exitosa');
            } else {
                return array('status' => false, 'msg' => 'Error en la actualización: ' . $stmt->error);
            }
        } catch (Exception $e) {
            return array('status' => false, 'msg' => 'Excepción: ' . $e->getMessage());
        }
    }

    public function removePermission($id)
    {
        try {
            $sql = "DELETE FROM permissions WHERE id = ?";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                return array('status' => true, 'msg' => 'Eliminación exitosa');
            } else {
                return array('status' => false, 'msg' => 'Error en la eliminación: ' . $stmt->error);
            }
        } catch (Exception $e) {
            return array('status' => false, 'msg' => 'Excepción: ' . $e->getMessage());
        }
    }
public function updateStaurasPermission($tatuas,$id,$yearcurrent) {
    try {
   
    $sql = " update permissions SET status = ?, updated_at = NOW() WHERE id = ? AND current_year = ? ";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("iis", $tatuas,$id,$yearcurrent);

   if ($stmt->execute()) {
      return array('status' => true, 'msg' => 'Actualización exitosa');
     } else {
      return array('status' => false, 'msg' => 'Error en la actualización: ' . $stmt->error);
    }
    


    } catch (Exception $e) {
       return array('status' => false, 'msg' => 'Excepción: ' . $e->getMessage());
     }

 }

    public function getPermissionsStaus_1($dateOnly)
    {
        try {
            $sql = "SELECT id ,end_date FROM permissions WHERE end_date <=   now()";
            $result = $this->conexion->conexion->query($sql);

            if ($result) {
                $permissions = array();
                while ($row = $result->fetch_assoc()) {
                    $permissions[] = $row;
                }

                return array('status' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $permissions);
            } else {
                return array('status' => false, 'msg' => 'Error al recuperar los datos: ' . $this->conexion->conexion->error);
            }
        } catch (Exception $e) {
            return array('status' => false, 'msg' => 'Excepción: ' . $e->getMessage());
        }
    }

     public function getPermissionTimeCurrenteMenor() {
        try {
            $sql = "SELECT * FROM permissions WHERE CURDATE() = DATE(created_at) AND TIME(NOW()) > end_time";
            $result = $this->conexion->conexion->query($sql);

            if ($result) {
                $permissions = array();
                while ($row = $result->fetch_assoc()) {
                    $permissions[] = $row;
                }

                return array('status' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $permissions);
            } else {
                return array('status' => false, 'msg' => 'Error al recuperar los datos: ' . $this->conexion->conexion->error);
            }
        } catch (Exception $e) {
            return array('status' => false, 'msg' => 'Excepción: ' . $e->getMessage());
        }
    }


public function getpermissionActives($date_init, $date_end, $search){
         $start_time = microtime(true);
        $sql = "SELECT p.idpersona, p.Apellidos, p.Nombre,
         IFNULL(CONCAT(pe.start_date, ' ~ ', pe.end_date), CONCAT(pe.start_time, ' ~ ', pe.end_time)) AS period,
         IF(pe.quantity_day IS NOT NULL AND pe.quantity_day != 0, pe.quantity_day, pe.quantity_hours) AS quantity,
         IF(pe.available_days IS NOT NULL AND pe.available_days != 0, pe.available_days, pe.available_times) AS availability,
        pe.id as id_permission, pe.status as isactive, pe.current_year,
        tp.name as name_permiss, tp.number_day,tp.id as id_tp_per
        FROM permissions pe
        LEFT JOIN type_permission tp ON pe.type_id = tp.id
        inner join personas p on p.idpersona = pe.id_people"; 

        if (!empty($date_end) && !empty($date_init)) {
          $sql .= " WHERE (pe.created_at BETWEEN '$date_init 00:00:00' AND '$date_end 23:59:59')";
        }
         // Agregar orden predeterminado y límite solo cuando no hay filtro de fecha
        if (empty($date_end) || empty($date_init)) {
           $sql .= " ORDER BY pe.created_at DESC LIMIT 10";
        }

        try {
            $result = $this->conexion->conexion->query($sql);
             $end_time = microtime(true);
              $query_time = round($end_time - $start_time, 3);
            if ($result) {
                
                $permis = array();
                while ($row = $result->fetch_assoc()) {
                    $permis[] = $row;
                }

                return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $permis,'consultation_time' => $query_time);
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos' . $this->conexion->conexion->error, 'data' => '','consultation_time' => $query_time);
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }


}

 ?>