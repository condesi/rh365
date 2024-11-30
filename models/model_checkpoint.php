<?php

require_once 'model/model_validations.php';

class Checkpoint
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

    public function registerCheckpoint($id, $id_user, $name, $longitude, $latitude, $haversine, $threshold, $status, $description, $photo)
    {
        try {
            if ($this->validations->isDuplicate('checkpoint', 'name', $name, $id, 'id')) {
                return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un punto de control con el mismo nombre');
            }

            $sql = "INSERT INTO checkpoint (id, id_user, name, longitude, latitude, haversine, threshold, status, description, photo, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("iisssssiss", $id, $id_user, $name, $longitude, $latitude, $haversine, $threshold, $status, $description, $photo);

            if ($stmt->execute()) {
                return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción' . $stmt->error, 'data' => '');
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }

    public function GetCheckpoints($search, $id_checkpoint){
        try {
            $sql = "SELECT id, id_user, name, longitude, latitude, haversine, threshold, status, description, photo, created_at, updated_at FROM checkpoint";

            if (!empty($id_checkpoint)) {
                $sql .= " WHERE id = ?";
            } elseif (!empty($search)) {
                $sql .= " WHERE name LIKE ? OR description LIKE ?";
            }
             $sql .= " ORDER BY created_at DESC";

            $stmt = $this->conexion->conexion->prepare($sql);
            if (!$stmt) throw new Exception('Error preparando la consulta SQL', 1);
            if (!empty($id_checkpoint)) {
                $stmt->bind_param("i", $id_checkpoint);
            } elseif (!empty($search)) {
                $searchTerm = "%$search%";
                $stmt->bind_param("ss", $searchTerm, $searchTerm); // "s" para cadena
            }

            $stmt->execute();
            if ($stmt->errno) throw new Exception('Error ejecutando la consulta SQL: ' . $stmt->error, 1);

            $result = $stmt->get_result();
            $checkpoints = array();
            while ($row = $result->fetch_assoc()) $checkpoints[] = $row;

            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $checkpoints);
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '');
        }
}


    public function updateCheckpoint($id, $name, $longitude, $latitude, $haversine, $threshold, $status, $description, $photo)
    {
        try {
            if ($this->validations->isDuplicate('checkpoint', 'name', $name, $id, 'id')) {
                return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un punto de control con el mismo nombre');
            }

            $sql = "UPDATE checkpoint SET name = ?, longitude = ?, latitude = ?, haversine = ?, threshold = ?, status = ?, description = ?, photo = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conexion->conexion->prepare($sql);
            $stmt->bind_param("sssssissi", $name, $longitude, $latitude, $haversine, $threshold, $status, $description, $photo, $id);

            if ($stmt->execute()) {
                return array('status' => true, 'auth' => true, 'msg' => 'Actualización exitosa', 'data' => '');
            } else {
                return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización' . $stmt->error, 'data' => '');
            }
        } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }
    }

    public function removeCheckpoint($id)
    {
        try {
            if (!$this->validations->recordExists('checkpoint', 'id', $id)) {
                return array('status' => false, 'auth' => true, 'msg' => 'No se encontró un punto de control con el ID especificado');
            }

            $sql = "DELETE FROM checkpoint WHERE id = ?";
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

    public function showCheckpoint($id)
    {
        $sql = "SELECT id, id_user, name, longitude, latitude, haversine, threshold, status, description, photo, created_at, updated_at FROM checkpoint WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $checkpoint = $result->fetch_assoc();
            return array('status' => true, 'auth' => true, 'msg' => 'Punto de control encontrado', 'data' => $checkpoint);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Punto de control no encontrado' . $stmt->error, 'data' => '');
        }
    }

    public function checkpointsSelect(){
         $sql = "SELECT id as id_checkpoint, id_user, name, longitude, latitude FROM checkpoint WHERE id_user IS NULL OR id_user=0";
        $result = $this->conexion->conexion->query($sql);

        if ($result) {
            $checkpoints = array();
            while ($row = $result->fetch_assoc()) {
                $checkpoints[] = $row;
            }
            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $checkpoints);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos' . $this->conexion->conexion->error, 'data' => '');
        }
    }
}

?>
