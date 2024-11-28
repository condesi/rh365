<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim(htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8'));
     $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));


    $min = 3;
    $max = 32;

    if (strlen($username) >= $min && strlen($username) <= $max) {
        require '../base/Controller_data.php';

        if (trim($username) !== clean_input_characters($username)) {
            $response = array('status' => false, 'auth' => false, 'msg' => 'Solicitud incorrecta', 'tipo' => 'alert-danger');
            echo json_encode($response);
            return;
        }

        require '../../models/models_usuarios.php';
        $user = new Usuario();

        if ($user->isUsernameExists($username)) {
            $result = $user->authenticateUser($username, $password);

            if ($result['status']) {
                $user_ = $result['data'];

                if ($user_['status'] == 1) {
                    session_start();
                    $_SESSION["iduser"] = $user_['iduser'];
                    $_SESSION["username"] = $username;
                    $_SESSION["namerole"] = $user_['namerole'];
                    $_SESSION["photo"] = $user_['photo'];
                    $_SESSION["role_id"] = $user_['role_id'];
                    $_SESSION['access'] = $user->AccesAuthorized($user_['role_id']);

                    setcookie("activo", 1, time() + 3600);

                    $response = array('status' => true, 'auth' => true, 'msg' => '¡Bienvenido ' . $user_['name'] . '!', 'tipo' => 'alert-success');
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $response = array('status' => false, 'auth' => false, 'msg' => 'La cuenta del usuario ' . $user_['username'] . ' está inactiva.', 'data' => '', 'tipo' => 'alert-info');
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                }
            } else {
                $response = array('status' => false, 'auth' => false, 'msg' => $result['msg'], 'tipo' => 'alert-danger');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $response = array('status' => false, 'auth' => false, 'msg' => 'El usuario ingresado no existe.', 'tipo' => 'alert-danger');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'El nombre de usuario debe tener entre ' . $min . ' y ' . $max . ' caracteres.', 'tipo' => 'alert-danger');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} else {
    $response = array('status' => false, 'auth' => false, 'msg' => 'SÓLO SE PERMITE MÉTODO POST.', 'tipo' => 'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}
//listo
?>
