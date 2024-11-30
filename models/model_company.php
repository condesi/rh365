<?php 

require_once 'model/model_validations.php';

class Company {
    private $conexion;
    private $validations;

    public function __construct() {
        require_once 'modelo_conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
        $this->validations = new Validations($this->conexion);
    }

   function Register_company($id, $name, $description, $address, $phone, $currency, $email, $logo, $flag, $ruc, $branch, $status,$isNormalAccess,$isGeoLocation, $isByCheckpoints) {
        if ($this->validations->isDuplicate('company', 'branch', $branch, $id, 'id')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un registro con el mismo nombre');
        }

        $sql = "INSERT INTO company (id, name, description, address, phone, currency, email, logo, flag, ruc, branch, created_at, updated_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";
    $stmt = $this->conexion->conexion->prepare($sql);
  $stmt->bind_param("ssssssssssss", $id, $name, $description, $address, $phone, $currency, $email, $logo, $flag, $ruc, $branch, $status);



        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción' . $stmt->error, 'data' => '');
        }
    }

    function Update_company($id, $name, $description, $address, $phone, $currency, $email, $logo, $flag, $ruc, $branch,$isNormalAccess,$isGeoLocation, $isByCheckpoints) {
        if ($this->validations->isDuplicate('company', 'name', $name, $id, 'id')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un registro con el mismo nombre');
        }

        $sql = "UPDATE company SET name = ?, description = ?, address = ?, phone = ?, currency = ?, email = ?, logo = ?, flag = ?, ruc = ?, branch = ?,isGeoLocation=?,isByCheckpoints=?,isNormalAccess=?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("ssssssssssiiis", $name, $description, $address, $phone, $currency, $email, $logo, $flag, $ruc, $branch,$isGeoLocation, $isByCheckpoints,$isNormalAccess, $id);

        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Actualización exitosa', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización' . $stmt->error, 'data' => '');
        }
    }

    function Remove_company($id) {
        if (!$this->validations->recordExists('company', 'id', $id)) {
            return array('status' => false, 'auth' => true, 'msg' => 'No se encontró un registro con el ID especificado');
        }

        $sql = "DELETE FROM company WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Eliminación exitosa', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la eliminación' . $stmt->error, 'data' => '');
        }
    }

    public function ShowCompany($id) {
        $sql = "SELECT id, name, description, address, phone, currency, email, logo, flag, ruc, branch, created_at, updated_at, status FROM company WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $company = $result->fetch_assoc();
            return array('status' => true, 'auth' => true, 'msg' => 'Empresa encontrada', 'data' => $company);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Empresa no encontrada' . $stmt->error, 'data' => '');
        }
    }
    public function getCompanyById($id) {
    $sql = "SELECT * FROM company WHERE id = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Comprobar si se encontró alguna fila
        if ($result->num_rows > 0) {
            $company = $result->fetch_assoc();
            return array('status' => true, 'auth' => true, 'msg' => 'Empresa encontrada', 'data' => $company);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Empresa no encontrada', 'data' => null);
        }
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error al ejecutar la consulta: ' . $stmt->error, 'data' => null);
    }
}


    public function getCompanyCustomers() {
       $sql = "SELECT id,  logo, flag FROM company";
       $stmt = $this->conexion->conexion->prepare($sql);
       if ($stmt->execute()) {
        $result = $stmt->get_result();
        $companies = $result->fetch_all(MYSQLI_ASSOC);
        return  $companies;
    } else {
        return null;
    }
}


    
}



 ?>