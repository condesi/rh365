
<?php

require_once 'model/model_validations.php';
class Usuario{
    private $conexion;
    private $validations;

    function __construct(){
        require_once 'modelo_conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
         $this->validations = new Validations($this->conexion);
    }
        

   public function RegisterUser($id,$name, $lastname, $username, $role_id, $photo, $email, $password, $phone, $company_id,$code,$people_id)
    {
        if ($this->validations->isDuplicate('users', 'username', $username, $id, 'iduser')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un usuario con el mismo user Name de usuario');
        }
        if ($this->validations->isDuplicate('users', 'code', $code, $id, 'iduser')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un usuario con el mismo Codigo '.$code );
        }
        $sql = "insert into users (name, lastname, username, role_id, photo, email, password, phone, company_id, created_at, updated_at, status,code,people_id)
                values (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1,?,?)";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("ssssssssssi", $name, $lastname, $username, $role_id, $photo, $email, $password, $phone, $company_id,$code,$people_id);
        if ($stmt->execute()) {
            return array('status' => true, 'auth' => true, 'msg' => 'Registro exitoso', 'data' => '');
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error en la inserción'. $stmt->error, 'data' => '');
        }
    }

    
    public function GetUsers()
    {
        $sql = "select iduser, name, lastname, username, role_id, photo, email, phone, company_id, users.created_at, users.updated_at, users.status,roles.namerole,code from users 
        inner join  roles on roles.idroles = users.role_id ORDER BY users.created_at DESC";
        
        $result = $this->conexion->conexion->query($sql);

        if ($result) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $users);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos', 'data' => '');
        }
    }


public function UpdateUser($id, $name, $lastname, $username, $role_id, $photo, $email, $phone, $company_id,$code,$people_id)
{
    if ($this->validations->isDuplicate('users', 'username', $username, $id, 'iduser')) {
        return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un usuario con el mismo nombre de usuario');
    }
     if ($this->validations->isDuplicate('users', 'code', $code, $id, 'iduser')) {
            return array('status' => false, 'auth' => true, 'msg' => 'Ya existe un usuario con el mismo Codigo '.$code );
        }

    $sql = "UPDATE users  SET name = ?, lastname = ?, username=?, role_id = ?, photo=?, code =?,people_id=?, updated_at = NOW() WHERE iduser = ?";

    $stmt = $this->conexion->conexion->prepare($sql);



    $stmt->bind_param("sssissii", $name, $lastname,$username,$role_id,$photo,$code,$people_id, $id);

    if ($stmt->execute()) {
        return array('status' => true, 'auth' => true, 'msg' => 'Actualización exitosa', 'data' => '');
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error en la actualización'. $stmt->error, 'data' => '');
    }
}

// Método para eliminar un usuario
public function RemoveUser($iduser)
{
    if (!$this->validations->recordExists('users', 'iduser', $iduser)) {
        return array('status' => false, 'auth' => true, 'msg' => 'No se encontró un usuario con el ID especificado');
    }

    $sql = "DELETE FROM users WHERE iduser = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $iduser);

    if ($stmt->execute()) {
        return array('status' => true, 'auth' => true, 'msg' => 'Eliminación exitosa', 'data' => '');
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error en la eliminación'. $stmt->error, 'data' => '');
    }
}

public function ShowUser($iduser)
{

    $sql = "SELECT iduser, name, lastname, username, role_id, photo, email, status, phone,code,people_id FROM users WHERE iduser = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $iduser);

    if($stmt->execute()){
      $result = $stmt->get_result(); // Obtener el resultado de la consulta
      $user = $result->fetch_assoc();
       return array('status' => true, 'auth' => true, 'msg' => 'Usuario encontrado', 'data' =>$user);
    }else{
      return array('status' => false, 'auth' => true, 'msg' => 'Usuario no encontrado'. $stmt->error, 'data' => '');
    } 
}

public function photoCurrentUser($iduser)
{
    $sql = "SELECT photo FROM users WHERE iduser = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $iduser);
    if ($stmt->execute() && ($user = $stmt->get_result()->fetch_assoc())) {
        return $user['photo'];
    }
    return null;
}


public function isUsernameExists($username)
{
    // Registrar mensaje en el archivo de registro del servidor
     error_log("Valor de \$username: " . $username);

    $sql = "SELECT COUNT(*) as userCount FROM users WHERE username = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $userCount = $row['userCount'];
    
    return $userCount > 0;
}




public function ChangeUserStatus($iduser, $newStatus)
{  
  
     $sql = "UPDATE users SET status = ? WHERE iduser = ?";
     $stmt = $this->conexion->conexion->prepare($sql);
      $stmt->bind_param("ss", $newStatus, $iduser);

      if ($stmt->execute()) {
        return array('status' => true, 'auth' => true, 'msg' => 'Estado actualizado correctamente', 'data' => '');
     } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error al actualizar el estado'. $stmt->error, 'data' => '');
      }
   
    
}

// Método para cambiar la contraseña de un usuario
public function ChangeUserPassword($iduser, $newPassword)
{
    $newPassword = password_hash($newPassword, PASSWORD_ARGON2I, ['cost' => 10]);
    $sql = "UPDATE users SET password = ? WHERE iduser = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("ss", $newPassword, $iduser);

    if ($stmt->execute()) {
        return array('status' => true, 'auth' => false, 'msg' => 'Contraseña actualizada correctamente, session destruido!', 'data' => '');
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error al actualizar la contraseña'. $stmt->error, 'data' => '');
    }
}

public function authenticateUser($username, $password)
{
   // Registrar mensaje en el archivo de registro del servidor
     error_log("Valor de \$username: " . $username. " and password is:". $password );

    $sql = "select iduser,name,lastname, username, photo,role_id,namerole,users.status, password from users
      inner join  roles on roles.idroles = users.role_id
     where username = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
          
            return ['status' => true, 'auth' => true, 'msg' => 'Inicio de sesión exitoso', 'data' => $user];
        } else {
            // La contraseña es incorrecta
            return ['status' => false, 'auth' => false, 'msg' => 'Contraseña incorrecta', 'data' => ''];
        }
    } else {
        // Error en la consulta
        return ['status' => false, 'auth' => false, 'msg' => 'Error al intentar autenticar'. $stmt->error, 'data' => ''];
    }
}



public function VerifyUserPassword($iduser, $currentPassword)
{
    $sql = "SELECT password FROM users WHERE iduser = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $iduser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        return false; // Usuario no encontrado
    }
    $user = $result->fetch_assoc();
    $hashedPassword = $user['password'];
    return password_verify($currentPassword, $hashedPassword);
}

public function IsNewPasswordValid($iduser, $currentPassword, $newPassword)
{
    $sql = "SELECT password FROM users WHERE iduser = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $iduser);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows !== 1) return false; 

    $user = $result->fetch_assoc();
    $hashedPassword = $user['password'];

    if (password_verify($currentPassword, $hashedPassword)) {
        // La contraseña actual es correcta
        if ($currentPassword === $newPassword) {
            return false; // La contraseña nueva es igual a la actual (no permitida)

        } else  return true; // La contraseña nueva es diferente de la actual (permitida)
           
        
    } else  return false; // La contraseña actual no es correcta
    
}

public function AccesAuthorized($idrole){
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


public function getUsersWithPagination($start, $length, $search, $dateInit, $dateEnd){
    $sql = "select iduser, name, lastname, username, role_id, photo, email, phone, company_id, users.created_at, users.updated_at, users.status, roles.namerole  from users
    INNER JOIN roles ON roles.idroles = users.role_id
    WHERE (name LIKE ? OR lastname LIKE ?)  LIMIT ?, ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $search = "%$search%";
    $stmt->bind_param("ssii", $search, $search, $start, $length);
    $stmt->execute();

    $result = $stmt->get_result();
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

public function getTotalAll($table)
{
    $sql = "SELECT COUNT(*) as total FROM $table";
    return ($result = $this->conexion->conexion->query($sql)) ?
     $result->fetch_assoc()['total'] : 0;
}

//SOLO PARA CREAR USUARIOS SE DEBEN  ESTAR PERSONAS TRBAJANDO//
public function listPeopleEstatusOn()
{
    $sql = "SELECT idpersona, Nombre, Apellidos, Dni FROM personas WHERE statedinicio = 'On'";
    $peoples = array();

    if ($consulta = $this->conexion->conexion->query($sql)) {
        while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
            $peoples[] = $consulta_VU;
        }

        return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $peoples);
    } else {
        return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos' . $this->conexion->conexion->error, 'data' => '');
    }
}


}
?>