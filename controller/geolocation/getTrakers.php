<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);

    try{
         $start = isset($_GET['date_init']) ? $_GET['date_init'] : "";
         $end = isset($_GET['date_end']) ? $_GET['date_end'] : "";
         $search = isset($_GET['search']) ? $_GET['search'] : "";
         $id_user = isset($_GET['id']) ? $_GET['id'] : "";
         $page = isset($_GET['paginate']) ? $_GET['paginate'] : 1;
         $xpage = 10; // Número de elementos por página
         $status=1;
     
           require '../../models/model_entry.php';
          $entry = new Entry();

         $response =$entry->getTrackerByIdUser($start,$end,$search, $id_user,$page,$xpage,$status);

        if(!empty( $response)){
        echo json_encode( $response);
          }else{
        echo '{
            "sEcho": 1,
            "iTotalRecords": "0",
            "iTotalDisplayRecords": "0",
            "aaData": []
           }';
         }
          } catch (Exception $e) {
        $response = array('status' => false, 'auth' => false, 'msg' => 'Error: ' . $e->getMessage(), 'data' => '', 'tipo' => 'alert-danger');
        echo json_encode($response);
        }


    } else {
        $response = array('status' => false, 'auth' => false, 'msg' => 'No Autorizado', 'data' => '');
        echo json_encode($response);
        return;
    }

}else {
    $response = array('status' => false,'auth' => false,'msg' => 'SOLO SE PUEDE GET.','data'=> '' ,'tipo'=>'alert-danger');
    echo json_encode($response);
}

 ?>