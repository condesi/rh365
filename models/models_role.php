<?php

require_once 'model/model_validations.php';
class Role{
    private $conexion;
    private $validations;

    public function __construct() {
        require_once 'modelo_conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
        $this->validations = new Validations($this->conexion);
    }  

function Register_role($id,$namerole){
    $id=null;
   if ($this->validations->isDuplicate('roles', 'namerole', $namerole, $id, 'idroles')) 
   {
      return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un registro con el mismo nombre');
    }

    $sql = "insert into roles (idroles, namerole, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("ss", $id, $namerole);
    if ($stmt->execute()) {
     return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
    } else {
      return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción'. $stmt->error, 'data' => '');
    }

    }

function get_role(){
   $sql = "select idroles, namerole, created_at, updated_at, status from roles";
   $result = $this->conexion->conexion->query($sql);
     if ($result) {
         $roles = array();
           while ($row = $result->fetch_assoc()) { $roles[] = $row; }

         return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $roles);
       } else {

        return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos' . $this->conexion->conexion->error, 'data' => '');
}
}

function Update_role($id, $namerole) {

    if ($this->validations->isDuplicate('roles', 'namerole', $namerole, $id,'idroles')) {

        return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un registro con el mismo nombre');
    }

    $sql = "update roles set namerole = ?, updated_at = NOW() WHERE idroles = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("ss", $namerole, $id);

    if ($stmt->execute()) {
        return array('status' => true, 'auth' => true, 'msg' => 'Actualización exitosa', 'data' => '');
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización'. $stmt->error, 'data' => '');
    }
}

function Remove_role($idrole){
    try{
    if (!$this->validations->recordExists('roles', 'idroles', $idrole)) {
        return array('status' => false, 'auth' => true, 'msg' => 'No se encontró un registro con el ID especificado');
    }

    $sql = "delete from roles where idroles = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $idrole);

    if ($stmt->execute()) {
        return array('status' => true, 'auth' => true, 'msg' => 'Eliminación exitosa', 'data' => '');
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error en la eliminación'. $stmt->error, 'data' => '');
    }
    } catch (Exception $e) {
            return array('status' => false, 'auth' => true, 'msg' => 'Excepción: ' . $e->getMessage(), 'data' => '');
        }

}

public function ShowRole($idrole)
{

    $sql = "select idroles, namerole, created_at, updated_at, status from roles  WHERE idroles = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $idrole);

    if($stmt->execute()){
      $result = $stmt->get_result(); // Obtener el resultado de la consulta
      $user = $result->fetch_assoc();
       return array('status' => true, 'auth' => true, 'msg' => 'Usuario encontrado', 'data' =>$user);
    }else{
      return array('status' => false, 'auth' => true, 'msg' => 'Usuario no encontrado'. $stmt->error, 'data' => '');
    }

   
}

 public function rolesSelect() {

        $sql = "select idroles,namerole from roles";
        $result = $this->conexion->conexion->query($sql);

        if ($result) {
            $users = array();
            while ($row = $result->fetch_assoc()) {$users[] = $row; }
            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $users);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos'. $this->conexion->conexion->error, 'data' => '');
        }
    }


public function  accessRole($idrole){
    
     $data = $this->getSidebar();
        $asignados = $this->sidebarAccesRole($idrole);

        $access = array();
         foreach ($asignados as $asignado) $access[$asignado['id_sidebar']] = true;
   
          $html = '  <input type="hidden" name="id_role" value="' . $idrole . '">';
          foreach ($data as $row) {
               $html .= '<div class="row invoice-info">';
               $html .= '<div class="col-6">' . $row['menu'] . '</div>';
               $html .= '<div class="col-6">';
               $html .= '<div class="toggle">';
               $html .= '<label>';
               $html .= '<input type="checkbox" name="access[]" value="' . $row['idsiderbar'] . '" ';
               if (isset($access[$row['idsiderbar']])) {
                   $html .= 'checked ';
               }
               $html .= '>';
               $html .= '<span class="button-indecator"></span>';
               $html .= '</label>';
               $html .= '</div>';
               $html .= '</div>';
               $html .= '</div>';
           }
    return $html;
}

public function getSidebar(){
    $sql = "SELECT idsiderbar, menu, type FROM siderbar";
    $stmt = $this->conexion->conexion->prepare($sql);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    } else {
        return [];
    }
}
public function sidebarAccesRole($idrole){
     $sql = "SELECT  id_sidebar FROM sidebaracces WHERE id_role = $idrole";
    $stmt = $this->conexion->conexion->prepare($sql);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    } else {
        return [];
    }
}



public function getAccessForRole($idrole) {
    $sql = "SELECT id_sidebar FROM sidebaracces WHERE id_role = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $idrole);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $permissions = [];
        foreach ($data as $row) {
            $permissions[] = $row['id_sidebar'];
        }
        return $permissions;
    } else {
        return [];
    }
}

public function addAccess($idrole, $permiso) {
        $sql = "INSERT INTO sidebaracces (id_role, id_sidebar) VALUES (?, ?)";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("ss", $idrole, $permiso);
        return $stmt->execute() ? true : false;
    }

    public function removeAccess($iduser, $permiso) {
        $sql = "DELETE FROM sidebaracces WHERE id_role = ? AND id_sidebar = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("ss", $iduser, $permiso);

        return $stmt->execute() ? true : false;
    }

 public function removeAllAccess($idrole) {
        $sql = "DELETE FROM sidebaracces WHERE id_role = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("s", $idrole);

        if ($stmt->execute()) {
        return array('status' => true, 'auth' => true, 'msg' => 'Todos los privilegios fueron eliminados del usuario.', 'data' => '');
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'No se puedo conpletar la operación.'. $stmt->error, 'data' => '');
    }
    }

}

 ?>