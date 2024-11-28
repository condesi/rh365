<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
    if (isset($_SESSION['username'])) {
        setcookie("activo", 1, time() + 3600);

        $id = htmlspecialchars($_POST['iduser'],ENT_QUOTES,'UTF-8');
        $name=htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');
        $lastname = htmlspecialchars($_POST['lastname'],ENT_QUOTES,'UTF-8');
        $username = htmlspecialchars($_POST['username'],ENT_QUOTES,'UTF-8');
        $role_id = htmlspecialchars($_POST['role_id'],ENT_QUOTES,'UTF-8');
        $isDeletPhoto = filter_var($_POST['isDeletPhoto'], FILTER_VALIDATE_BOOLEAN);
        $code = htmlspecialchars($_POST['code'],ENT_QUOTES,'UTF-8');
        $people_id = htmlspecialchars($_POST['people_id'],ENT_QUOTES,'UTF-8');

        require '../base/Controller_data.php';
        if (trim($username) !== clean_input_characters($username)) {
            $response = array('status' => false, 'auth' => false, 'msg' => 'Bad Request', 'data' => '');

            echo json_encode($response);
            return;
        }
        require '../../models/models_usuarios.php';
        $user = new Usuario();

        // Verificar si se ha eliminado la foto
        $photoCurrent = $user->photoCurrentUser($id);
        if ($isDeletPhoto === true) {
            try {
                if ($photoCurrent && file_exists($photoCurrent)) {
                    unlink($photoCurrent);
                    $photoCurrent = '';
                }
            } catch (Exception $e) {
                $response = array('status' => false, 'msg' => 'Error al eliminar la foto: ' . $e->getMessage());
                echo json_encode($response);
                return;
            }
        }

        // Manejar archivo
        if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
            require '../base/ControllerManagementFiles.php';
            try {
                $fileName = uploadPhotoAll("photo", $username,'user');
            } catch (Exception $e) {
                $response = array('status' => false, 'msg' => 'Error al subir la foto: ' . $e->getMessage());
                echo json_encode($response);
                return;
            }
        }

        $photo = (isset($fileName) && $fileName) ? $fileName : $photoCurrent;

        try {
            $response = $user->UpdateUser($id, $name, $lastname, $username, $role_id, $photo, '', '', 1, $code, $people_id);
            echo json_encode($response);
            exit();
        } catch (Exception $e) {
            $response = array('status' => false, 'msg' => 'Error al actualizar el usuario: ' . $e->getMessage());
            echo json_encode($response);
        }
    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        
        echo json_encode($response);
    }

     echo json_encode($response);
} catch (Exception $e) {
    // Si ocurre un error, captura la excepción y envía una respuesta JSON con el mensaje de error
    $response = array('status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage());
    http_response_code(500); // Establece el código de estado HTTP 500 (Error interno del servidor)
    echo json_encode($response);
}

} else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
   
    echo json_encode($response);
}
//listo
?>
