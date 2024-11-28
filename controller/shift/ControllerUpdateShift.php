<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);


        $jsonData = file_get_contents('php://input');
        $lastModified = json_decode($jsonData, true);
        require '../../models/models_shifts.php';
        $shift = new Shift();

         if ($lastModified !== null) {
            foreach ($lastModified as $data) {
             $dayName = trim($shift->reverseTranslateDayName($data['nameday']));
             if($dayName===$data['nameday']) return false;

                 $response =$shift->updateShift($data['id'],$dayName ,sprintf($data['dayNumber']),  $data['morningEntryTime'],  $data['morningExitTime'], $type='normal',
                 $data['afternoonEntryTime'], $data['afternoonExitTime'], $status=1);

                  if (!$response['status']) {
                    echo json_encode($response);return;
                  }


            }
            echo json_encode($response);
        } else {
            $response = array('status' => false, 'auth' => false, 'msg' => 'No hay nada para actualizar!.', 'data' => '');
            echo json_encode($response);
        return;
        }

    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        echo json_encode($response);
        return;
    }

}else {
   $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE POST.','data'=> '' ,'tipo'=>'alert-danger');
    echo json_encode($response);
}

 ?>