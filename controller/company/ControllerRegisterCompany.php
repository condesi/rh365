<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_SESSION['username'])) {
     setcookie("activo", 1, time() + 3600);
        require '../../models/model_company.php';
        $companny = new Company();

        // Validar y filtrar los datos del formulario
        $id='';
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
        $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
        $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
        $currency = isset($_POST['currency']) ? htmlspecialchars($_POST['currency']) : '';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $ruc = isset($_POST['ruc']) ? htmlspecialchars($_POST['ruc']) : '';
        $branch = isset($_POST['branch']) ? htmlspecialchars($_POST['branch']) : '';

        $isNormalAccess = isset($_POST['isNormalAccess']) ? htmlspecialchars($_POST['isNormalAccess']) : 1;
        $isGeoLocation = isset($_POST['isGeoLocation']) ? htmlspecialchars($_POST['isGeoLocation']) : 0;
        $isByCheckpoints = isset($_POST['isByCheckpoints']) ? htmlspecialchars($_POST['isByCheckpoints']) : 0;
        if ($isGeoLocation == 1) {
             $isByCheckpoints = 0;
         } elseif ($isByCheckpoints == 1) {
             $isGeoLocation = 0;
         }
        $status = 1;  // O cualquier otro valor numérico que necesites
        $currenCompany = $companny->getCompanyCustomers();

           if (isset($_POST['flagPath']) && $_POST['flagPath']==null || $_POST['flagPath']==""){
              if (file_exists($currenCompany[0]['flag'])) {unlink($currenCompany[0]['flag']);
               isset($currenCompany[0]['flag']) ? $currenCompany[0]['flag']="": '' ;}
           }
           if (isset($_POST['logoPath']) && $_POST['logoPath']==null || $_POST['logoPath']==""){
              if (file_exists($currenCompany[0]['logo'])) { unlink($currenCompany[0]['logo']); 
              isset($currenCompany[0]['logo']) ? $currenCompany[0]['logo']="":'';}
           }


        // Manejar las imágenes
         require '../base/ControllerManagementFiles.php';
            if (isset($_FILES["logo"]["name"]) && $_FILES["logo"]["error"] == UPLOAD_ERR_OK) {
             $logoPath= uploadPhotoAll("logo", "logo",'');
            }
          //manegar archivo
         if (isset($_FILES["flag"]["name"]) && $_FILES["flag"]["error"] == UPLOAD_ERR_OK) {
             $flagPath= uploadPhotoAll("flag", "banner",'');
         }
          
          $logo = isset($logoPath) ? $logoPath : $currenCompany[0]['logo'];
          $flag = isset($flagPath) ? $flagPath : $currenCompany[0]['flag'];


        if (count($currenCompany ) > 0) {
            $idCompany = isset($currenCompany[0]['id']) ? $currenCompany[0]['id'] : 1;
            $result = $companny->Update_company($idCompany, $name, $description, $address, $phone, $currency, $email, $logo, $flag, $ruc, $branch,$isNormalAccess,$isGeoLocation, $isByCheckpoints);
            
        }else{
           $result = $companny->Register_company($id, $name, $description, $address, $phone, $currency, $email, $logo, $flag, $ruc, $branch, $status,$isNormalAccess,$isGeoLocation, $isByCheckpoints);
        }
       
        echo json_encode($result);

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