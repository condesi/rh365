<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //iniciamos la sesion
	session_start();
	require '../../models/modelo_hextra.php';
     $extra = new Hextras;
	if (isset($_SESSION['username'])) {
		setcookie("activo", 1, time() + 3600);
	} else {

		$response = array('status' => true,'auth' => false,'msg' => 'No Autorizado.'.http_response_code(403),'data'=> '');
		return json_encode($response);
	}

	$idpersona = htmlspecialchars($_POST['idpersona'], ENT_QUOTES, 'UTF-8');

	$consulta= $extra->PaymentDateExtra($idpersona);
	$response = array('status' => true,'auth' => true,'msg' => 'ok','data'=> $consulta);
	echo json_encode($response);
} else {

	$response = array('status' => true,'auth' => false,'msg' => 'SOLO SE PUEDE POST.error:405','data'=>"");
	echo json_encode($response);
}
?>