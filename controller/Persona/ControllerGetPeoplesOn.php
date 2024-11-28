
<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_SESSION['username'])) {
		setcookie("activo", 1, time() + 3600);

		require '../../models/models_usuarios.php';
		$user = new Usuario();
		$consulta = $user->listPeopleEstatusOn();
		echo json_encode($consulta);
		
	} else {
		$response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
		http_response_code(403);
		echo json_encode($response);
		return;
	}

}else {
	$response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
	http_response_code(405);
	echo json_encode($response);
}
//listo
?>