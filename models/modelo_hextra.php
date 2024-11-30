<?php
    class Hextras{
        private $conexion;
        function __construct(){
            require_once 'modelo_conexion.php';
            $this->conexion = new conexion();
            $this->conexion->conectar();
        }

 function Listar_Empleados_Hextra(){
 	
  //$sql="SELECT  idpersona,Nombre,Apellidos,Dni,Moneda,Salario,Estado FROM personas";
  $sql="SELECT  idpersona,Nombre,Apellidos,Dni,Moneda,Salario,Estado FROM jornada
          INNER JOIN personas ON personas.idpersona = jornada.personaid";
            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;

                }
                return $arreglo;
                $this->conexion->cerrar();
            }
            
 }


function Registrar_Horas_Extras($idempleado,$cantidad,$total,$fechaActual,$year){

 $sql = "INSERT INTO horaextra(idepersona, hextra, total, fecharegistro,year,stadohoral) 
      VALUES ('$idempleado','$cantidad','$total','$fechaActual','$year','Pendiente')";
       if ($consulta = $this->conexion->conexion->query($sql)) {
           
     $response = array('status' => true,'auth' => true,'msg' => 'El registro se completo corectamente.','data'=> 1);
    return json_encode($response);
                
    }else{
   $response = array('status' => true,'auth' => true,'msg' => 'Ocurrio un error al registrar','data'=> 0);
    return json_encode($response);
 }

}

function Remover_Personal_Hextra($idempleado,$idxestra){
$sql=   "DELETE FROM horaextra WHERE idhextra ='$idxestra' and idepersona = '$idempleado'";
      if ($consulta = $this->conexion->conexion->query($sql)) {

         $response = array('status' => true,'auth' => true,'msg' => 'Se elimino corectamente','data'=>1);
    return json_encode($response);
        
      }else{
        $response = array('status' => true,'auth' => true,'msg' => 'No se completo el registro','data'=>0);
    return json_encode($response);
      }

}

function Extrae_Horas_Personal($idpersona){
 $sql="SELECT idhextra,fecharegistro, hextra, total,  year FROM horaextra where idepersona='$idpersona'";
     $arreglo = array();
   if ($consulta = $this->conexion->conexion->query($sql)) {
     while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
         $arreglo[]=$consulta_VU;
        }
     return $arreglo;
     $this->conexion->cerrar();
 }

}

/////////////////////////////////////////
//////////SECCION DE HORAS EXTRA////////////
////////////////////////////////////////


function Listar_Horas_Extras_Empleados() {
    $sql = "SELECT idepersona, Nombre, Apellidos, Dni,Moneda, Salario, fecharegistro, hextra, total, stadohoral 
FROM horaextra
            INNER JOIN personas ON personas.idpersona = horaextra.idepersona";
    $arreglo = array();
    $valoresUnicos = array();  // Array para realizar un seguimiento de los valores únicos

    if ($consulta = $this->conexion->conexion->query($sql)) {
        while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
            $valorUnico = $consulta_VU['idepersona'];  // Columna que quieres que sea única
            
            // Verificar si el valor único ya existe en el array
            if (!in_array($valorUnico, $valoresUnicos)) {
                $consulta_VU['fechainicio'] = $consulta_VU['fecharegistro'];  // Almacenar la primera fecha de registro
                $consulta_VU['fechafin'] = $consulta_VU['fecharegistro'];  // Almacenar la última fecha de registro
                
                $arreglo["data"][] = $consulta_VU;
                $valoresUnicos[] = $valorUnico;  // Agregar el valor único al array
            } else {
                // Si el valor ya existe, actualizar la fecha de fin
                $indice = array_search($valorUnico, $valoresUnicos); // Obtener el índice del valor repetido
                $arreglo["data"][$indice]['fechafin'] = $consulta_VU['fecharegistro'];
                
                // Sumar las columnas "total" y "hextra" al valor existente en el arreglo
               $arreglo["data"][$indice]['total'] += $consulta_VU['total'];
               $arreglo["data"][$indice]['hextra'] += $consulta_VU['hextra'];

               $arreglo["data"][$indice]['total'] = number_format($arreglo["data"][$indice]['total'], 2, '.', '');
            }
        }
        
        $this->conexion->cerrar();  // Cerrar la conexión a la base de datos
    }

    return $arreglo;
}



function Registrar_Pagos_Horas_Extras($idextra,$fecha,$horastotal,$montototal,$idpersona,$fechaActual){

 $sql = "INSERT INTO pagoshextra (idhextra, fechas, horas, monto, idpersona, datecreated, descripcion,stated) 
      VALUES ('$idextra','$fecha','$horastotal','$montototal','$idpersona','$fechaActual','EXT','Pagado')";
         if ($consulta = $this->conexion->conexion->query($sql)) {
          
         return 1;
                
         }else{
             return 0;
       }

}

function Extrae_Horas_Empleados($idpersona){
 $sql="SELECT idhextra, hextra, total, fecharegistro, year FROM horaextra where idepersona='$idpersona'";
     $arreglo = array();
   if ($consulta = $this->conexion->conexion->query($sql)) {
     while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
         $arreglo[]=$consulta_VU;
        }
     return $arreglo;
     $this->conexion->cerrar();
 }

}




function DarDeBajasHorasExtras($idextra,$idpersona){
  $sql="DELETE FROM horaextra where idhextra ='$idextra' and idepersona ='$idpersona'";
  if($consulta =$this->conexion->conexion->query($sql)){
    return 1;
  }else{
    return 0;
  }
}



///////////////////////////////////////////////////
/////////////SECCION DE REPORTES DE PAGO/////////////
////////////////////////////////////////////////

function listar_Pagos_Extras(){

 $sql = "SELECT personas.idpersona, Nombre, Apellidos, Dni,fechas, horas, monto,datecreated, stated FROM pagoshextra
            INNER JOIN personas ON personas.idpersona = pagoshextra.idpersona";
    $arreglo = array();
    $valoresUnicos = array();  // Array para realizar un seguimiento de los valores únicos

    if ($consulta = $this->conexion->conexion->query($sql)) {
        while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
            $valorUnico = $consulta_VU['idpersona'];  // Columna que quieres que sea única
            
            // Verificar si el valor único ya existe en el array
            if (!in_array($valorUnico, $valoresUnicos)) {
                $consulta_VU['fechainicio'] = $consulta_VU['fechas'];  // Almacenar la primera fecha de registro
                $consulta_VU['fechafin'] = $consulta_VU['fechas'];  // Almacenar la última fecha de registro
                
                $arreglo["data"][] = $consulta_VU;
                $valoresUnicos[] = $valorUnico;  // Agregar el valor único al array
            } else {
                // Si el valor ya existe, actualizar la fecha de fin
                $indice = array_search($valorUnico, $valoresUnicos); // Obtener el índice del valor repetido
                $arreglo["data"][$indice]['fechafin'] = $consulta_VU['fechas'];
                
                // Sumar las columnas "monto" y "horas" al valor existente en el arreglo
                $arreglo["data"][$indice]['monto'] += $consulta_VU['monto'];
                $arreglo["data"][$indice]['horas'] += $consulta_VU['horas'];
            }
        }
        
        $this->conexion->cerrar();  // Cerrar la conexión a la base de datos
    }

    return $arreglo;

}

function Listar_Show_Pagos_Empleados($idepersona){

  $sql="SELECT  fechas, horas, monto,descripcion, stated FROM pagoshextra where idpersona='$idepersona'";

  $arreglo = array();
  if ($consulta = $this->conexion->conexion->query($sql)) {
    while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

      $arreglo['data'][]=$consulta_VU;

    }
    return $arreglo;
    $this->conexion->cerrar();
  }

}


function listar_Pagos_Extras_Range($fechaInicio,$fechaFin){

 $sql = "SELECT personas.idpersona, Nombre, Apellidos, Dni,fechas, horas, monto,datecreated, stated FROM pagoshextra
 INNER JOIN personas ON personas.idpersona = pagoshextra.idpersona where (datecreated BETWEEN '$fechaInicio' AND '$fechaFin')";
 $arreglo = array();
    $valoresUnicos = array();  // Array para realizar un seguimiento de los valores únicos

    if ($consulta = $this->conexion->conexion->query($sql)) {
      while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
            $valorUnico = $consulta_VU['idpersona'];  // Columna que quieres que sea única
            
            // Verificar si el valor único ya existe en el array
            if (!in_array($valorUnico, $valoresUnicos)) {
                $consulta_VU['fechainicio'] = $consulta_VU['fechas'];  // Almacenar la primera fecha de registro
                $consulta_VU['fechafin'] = $consulta_VU['fechas'];  // Almacenar la última fecha de registro
                
                $arreglo["data"][] = $consulta_VU;
                $valoresUnicos[] = $valorUnico;  // Agregar el valor único al array
              } else {
                // Si el valor ya existe, actualizar la fecha de fin
                $indice = array_search($valorUnico, $valoresUnicos); // Obtener el índice del valor repetido
                $arreglo["data"][$indice]['fechafin'] = $consulta_VU['fechas'];
                
                // Sumar las columnas "monto" y "horas" al valor existente en el arreglo
                $arreglo["data"][$indice]['monto'] += $consulta_VU['monto'];
                $arreglo["data"][$indice]['horas'] += $consulta_VU['horas'];
              }
            }

        $this->conexion->cerrar();  // Cerrar la conexión a la base de datos
      }

      return $arreglo;

    }



function PaymentDateExtra($idpersona){

   $sql = "SELECT pagoshextra.idpersona, monto ,monto as montoP,idhextra, fechas,datecreated , datecreated as fechaoperacion, descripcion, Nombre, Apellidos, Dni FROM pagoshextra
  inner join  personas on personas.idpersona = pagoshextra.idpersona
  WHERE pagoshextra.idpersona = $idpersona";

  $arreglo = array();
  if ($consulta = $this->conexion->conexion->query($sql)) {
    while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

      $arreglo[]=$consulta_VU;
    }
    return $arreglo;
    $this->conexion->cerrar();
  }
}

}
?>

