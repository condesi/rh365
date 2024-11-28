<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_SESSION['username'])) {
        setcookie("activo", 1, time() + 3600);

        require '../../models/model_company.php';
        $companyModel = new Company();

        // Obtener el ID de la compañía desde la URL o de donde sea que lo tengas
        $companyId = isset($_GET['id']) ? $_GET['id'] : '';

        // Obtener los detalles de la compañía por su ID
        $companyDetails = $companyModel->getCompanyById($companyId);
      echo json_encode($companyDetails);

    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        http_response_code(403);
        echo json_encode($response);
        return;
    }
} else {
    $response = array('status' => false, 'auth' => false, 'msg' => 'Método no permitido', 'data' => '', 'tipo' => 'alert-danger');
    http_response_code(405);
    echo json_encode($response);
}
?>
