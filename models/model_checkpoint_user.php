<?php

require_once 'model/model_validations.php';

class CheckpointUser
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

    public function registerCheckpointUser($id_user, $id_checkpoint, $status, $description)
    {
        try {
            $sql = "INSERT INTO checkpoint_user (id_user, id_checkpoint, status, description, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("iiis", $id_user, $id_checkpoint, $status, $description);

            if ($stmt->execute()) {
                return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción' . $stmt->error, 'data' => '');
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }

    public function GetCheckpointUsers($search, $id_checkpoint){
        try {
            $sql = "SELECT id, id_user, id_checkpoint, status, description, created_at, updated_at FROM checkpoint_user";

            if (!empty($id_checkpoint)) {
                $sql .= " WHERE id_checkpoint = ?";
            } elseif (!empty($search)) {
                $sql .= " WHERE description LIKE ?";
            }

            $stmt = $this->conexion->conexion->prepare($sql);
            if (!$stmt) throw new Exception('Error preparando la consulta SQL', 1);
            if (!empty($id_checkpoint)) {
                $stmt->bind_param("i", $id_checkpoint);
            } elseif (!empty($search)) {
                $searchTerm = "%$search%";
                $stmt->bind_param("s", $searchTerm); // "s" para cadena
            }

            $stmt->execute();
            if ($stmt->errno) throw new Exception('Error ejecutando la consulta SQL: ' . $stmt->error, 1);

            $result = $stmt->get_result();
            $checkpointUsers = array();
            while ($row = $result->fetch_assoc()) $checkpointUsers[] = $row;

            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $checkpointUsers);
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '');
        }
    }
   

    public function updateCheckpointUser($id, $id_user, $id_checkpoint, $status, $description)
    {
        try {
            $sql = "UPDATE checkpoint_user SET id_user = ?, id_checkpoint = ?, status = ?, description = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("iiisi", $id_user, $id_checkpoint, $status, $description, $id);

            if ($stmt->execute()) {
                return array('status' => true, 'auth' => true, 'msg' => 'Actualización exitosa', 'data' => '');
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización' . $stmt->error, 'data' => '');
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }

    public function removeCheckpointUser($id)
    {
        try {
            $sql = "DELETE FROM checkpoint_user WHERE id = ?";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                return array('status' => true, 'auth' => true, 'msg' => 'Eliminación exitosa', 'data' => '');
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error en la eliminación' . $stmt->error, 'data' => '');
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }

    public function showCheckpointUser($id) {
    $sql = "SELECT cu.id, cu.id_user, cu.id_checkpoint, cu.status, cu.description, cp.name, cp.longitude, cp.latitude,cp.threshold,cp.haversine
            FROM checkpoint_user cu
            INNER JOIN checkpoint cp ON cp.id = cu.id_checkpoint";
    if (!empty($id)) {
        $sql .= " WHERE cu.id_user = ?";
    }
    $sql .= " ORDER BY cu.id DESC";

    $stmt = $this->conexion->conexion->prepare($sql);

    if (!empty($id)) {
        $stmt->bind_param("i", $id);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $checkpointUsers = $result->fetch_all(MYSQLI_ASSOC);

        $message = !empty($checkpointUsers) ? 'Registros encontrados' : 'Registros no encontrados';
        return array('status' => true, 'auth' => true, 'msg' => $message, 'data' => $checkpointUsers);
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error al ejecutar la consulta: ' . $stmt->error, 'data' => '');
    }
}


public function idCheckPointCurrent($id)
{
    $sql = "SELECT id_checkpoint FROM checkpoint_user WHERE id = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $id);
    if ($stmt->execute() && ($checkpoint = $stmt->get_result()->fetch_assoc())) {
        return $checkpoint['id_checkpoint'];
    }
    return null;
}

    public function existeCheckpointUser($id_user, $id_checkpoint){
        try {
            $sql = "SELECT COUNT(*) as count FROM checkpoint_user WHERE id_user = ? AND id_checkpoint = ?";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("ii", $id_user, $id_checkpoint);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row['count'] > 0; // Devuelve true si existe al menos un registro, false si no existe ninguno
        } catch (Exception $e) {
            return false; // En caso de error, devuelve false
        }
    }

     public function updateStautsCheckpointByIdPoint($id_user, $checkpoint_id){
        try {
           
            $sql = "UPDATE checkpoint SET id_user = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("si", $id_user, $checkpoint_id);

            if ($stmt->execute()) {
                return array('status' => true, 'auth' => true, 'msg' => 'La operación  exitosa', 'data' => '');
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización' . $stmt->error, 'data' => '');
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }


}

?>
