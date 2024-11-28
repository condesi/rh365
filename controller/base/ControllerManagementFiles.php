<?php

function uploadPhotoAll($inputName, $name, $type) {
    $phat = '../../images/' . (isset($type) ? $type . '/' : '');
    $tempFileName = $_FILES[$inputName]['tmp_name'];

    if (!empty($tempFileName) && is_uploaded_file($tempFileName)) {
        $fileName = basename($_FILES[$inputName]['name']);

        if ($type == 'user' || $type == 'people') {
            $fileName = $name . '_' . date('YmdHis') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        } else {
            $fileName = $name . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        }

        $targetPath = $phat . $fileName;

       // Verificar si ya existe un archivo con el mismo prefijo antes del guion bajo
          $filesWithSamePrefix = glob($phat. $name . '_*');
          foreach ($filesWithSamePrefix as $file) {
              if (file_exists($file)) {
                  try {
                      unlink($file);
                  } catch (Exception $e) {
                      // Capturar la excepción y mostrar un mensaje de error en la respuesta JSON
                      $response = array('status' => false, 'msg' => 'Error al intentar eliminar el archivo: ' . $e->getMessage());
                      echo json_encode($response);
                      return;
                  }
              }
          }


        if (file_exists($targetPath)) {
            // El archivo ya existe, lo reemplazamos
            try {
                unlink($targetPath);
            } catch (Exception $e) {
                echo 'Error al intentar eliminar el archivo existente: ' . $e->getMessage();
            }
        }

        try {
            if (move_uploaded_file($tempFileName, $targetPath)) {
                return $targetPath; // Devolver la ruta del archivo
            }
        } catch (Exception $e) {
            echo 'Error al mover el archivo subido: ' . $e->getMessage();
        }
    }

    return null; // Devolver null si no se pudo subir la imagen
}


function uploadDocumentAll($inputName, $name, $type) {
    $phat = '../../files/document/' . (isset($type) ? $type . '/' : '');

    $files = $_FILES[$inputName];
    $nombresArchivos = array();

    // Recorrer cada archivo
    for ($i = 0; $i < count($files['name']); $i++) {
        $nameFile = $files['name'][$i];
        $phatTem = $files['tmp_name'][$i];
   
        $temFileName = $phat . "CV_" . $name . "_" . $nameFile;
        $filesWithSamePrefix = glob($phat . "CV_" . $name . "_*");
        foreach ($filesWithSamePrefix as $file) unlink($file);
        
        // Mover el archivo a la carpeta destino
        if (move_uploaded_file($phatTem, $temFileName)) {
            // El archivo se ha movido exitosamente
            $nombresArchivos[] = $temFileName; // Cambiado a $temFileName
        } else {
            // Hubo un error al mover el archivo
        }
    }
    return $nombresArchivos[0];
}

?>

