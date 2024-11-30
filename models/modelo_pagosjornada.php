<?php
    class Pagos{
        private $conexion;
        function __construct(){
            require_once 'modelo_conexion.php';
            $this->conexion = new conexion();
            $this->conexion->conectar();
            //adelantos,montoextra,
          // DAY:jornada','ADL :adelanto', 'EXT :extra
        }

 function Listar_Personal_On(){
 	
  $sql="SELECT idpersona,Nombre,Apellidos,Dni,Salario,Moneda,FechaInicio,fechapago,EstadoCuenta FROM jornada
        INNER JOIN personas ON jornada.personaid = personas.idpersona";
            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;

                }
                return $arreglo;
                $this->conexion->cerrar();
            }
            
 }

function ExtraerTotalCostoHorasExtrasPersona($idpersona){
    $sql = "SELECT SUM(total) AS TotalMonto FROM horaextra WHERE idepersona='$idpersona'";
    $totalMonto = 0.0;

    if ($consulta = $this->conexion->conexion->query($sql)) {
        if ($consulta_VU = mysqli_fetch_assoc($consulta)) {
            $totalMonto = (double) $consulta_VU['TotalMonto'];
        }
    }

    return round($totalMonto, 2);
 $this->conexion->cerrar();

}
function ExtraerTotalCostoAdelantosPersona($idpersona){
 $sql = "SELECT SUM(Monto) AS TotalMonto FROM adelantos WHERE idpersona='$idpersona'";
    $totalMonto = 0.0;

    if ($consulta = $this->conexion->conexion->query($sql)) {
        if ($consulta_VU = mysqli_fetch_assoc($consulta)) {
            $totalMonto = (double) $consulta_VU['TotalMonto'];
        }
    }
    return round($totalMonto, 2);
    $this->conexion->cerrar();

      
}

function getAllAdvancesPeople($idpersona){
  $sql = "SELECT idpersona, Monto, Fecharegisto FROM adelantos where idpersona='$idpersona'";
  $result = $this->conexion->conexion->query($sql);
  if ($result) {
    $advances = array();
    while ($row = $result->fetch_assoc()) {
      $advances[] = $row;
    }
    return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $advances);
  } else {
    return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos', 'data' => '');
  } 

}
function getAllPeopleExtraHours($idpersona){
  $sql = "SELECT idepersona, hextra, total, fecharegistro FROM horaextra where idepersona='$idpersona'";
        $result = $this->conexion->conexion->query($sql);
        if ($result) {
            $extraHours = array();
            while ($row = $result->fetch_assoc()) {
                $extraHours[] = $row;
            }
            return array('status' => true, 'auth' => true, 'msg' => 'Datos recuperados con éxito', 'data' => $extraHours);
        } else {
            return array('status' => false, 'auth' => true, 'msg' => 'Error al recuperar los datos', 'data' => '');
        } 

}



function PagosMensualesPersonal($mesSeleccionado,$fechaspagado, $monto,$idpersona,$datecurrent){
 $sql = "INSERT INTO pagosjornada (idpersona, montoP, Description, fechasPagados, fechaoperacion, stado,type)
 VALUES ('$idpersona','$monto','Mensualidad', '$fechaspagado','$datecurrent','PAGADO','DAY')";
 if ($consulta = $this->conexion->conexion->query($sql)) {

   return 1;

 }else{
  return 0;
}

}

//new fuction implements

function ActualizarEstadoJornadaPersonalOn($idpersona,$fechapagado,$fechaproximo){
     $sql = "UPDATE jornada SET FechaInicio = '$fechapagado', fechapago='$fechaproximo' WHERE personaid = '$idpersona'";
    if ($consulta = $this->conexion->conexion->query($sql)) {

      $this->Update_State_estado_CuentaPersonal($idpersona, "Procesando...");
        return 1;
        
    }else{
        return 0;
    }

}


function PagarAdelantosPersonalOn($idpersona,$datecurrent,$dateoperacion,$totaladelantos){
 $sql = "INSERT INTO pagosjornada (idpersona, montoP, Description, fechasPagados, fechaoperacion, stado,type)
         VALUES ('$idpersona','$totaladelantos','pago por adelantado personal', '$dateoperacion','$datecurrent','PAGADO','ADL')";
          if ($consulta = $this->conexion->conexion->query($sql)) {
             $this->QuitarActualizarAdelantosPagado($idpersona);

         return 1;
              
          }else{
             $error = $this->conexion->conexion->error;
              return "Error: " . $error; // Retorna el mensaje de error específico
          }
}

function QuitarActualizarAdelantosPagado($idpersona){
     $sql="DELETE FROM adelantos where idpersona ='$idpersona'";
  if($consulta =$this->conexion->conexion->query($sql)){
    return 1;
  }else{
    return 0;
  }

}


function PagarHorasExtrasPersonalOn($idpersona,$Hours, $dateoperacion,$datecurrent,$totalextra){
 $sql = "INSERT INTO pagosjornada (idpersona, montoP,extrahours, Description, fechasPagados, fechaoperacion, stado,type)
 VALUES ('$idpersona','$totalextra','$Hours','pago horas extras', '$dateoperacion','$datecurrent','PAGADO','EXT')";
 if ($consulta = $this->conexion->conexion->query($sql)) {
  $this->QuitarActualizarHorasExtrasPagado($idpersona);
  return 1;

}else{
  return 0;
}

}

function QuitarActualizarHorasExtrasPagado($idpersona){
     $sql="DELETE FROM horaextra where idepersona ='$idpersona'";
  if($consulta =$this->conexion->conexion->query($sql)){
    return 1;
  }else{
    return 0;
  }  
}


function Filtrar_Reportes_Pagosjornada($fechaInicio,$fechaFin){
 $sql="select  personas.idpersona,Nombre, Apellidos,dni, montoP, Description, fechasPagados, fechaoperacion,type FROM pagosjornada
   inner join  personas on personas.idpersona = pagosjornada.idpersona  
 WHERE  (fechaoperacion BETWEEN '$fechaInicio' AND '$fechaFin')"; 

            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;
                }
                return $arreglo;
           $this->conexion->cerrar();
     }
}

function Listar_report_Jornada(){
   $sql="select  personas.idpersona,Nombre, Apellidos,dni, montoP, Description, fechasPagados, fechaoperacion,type FROM pagosjornada
   inner join  personas on personas.idpersona = pagosjornada.idpersona ";
            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
             $arreglo['data'][]=$consulta_VU;
         }
       return $arreglo;
         $this->conexion->cerrar();
      } 
}

function PagosRealizadosMensuales($idepersona,$dateoperation){

$sql="SELECT Nombre, Apellidos,dni,fechasPagados,Description,montoP,adelantos,montoextra FROM pagosjornada
inner join  personas on personas.idpersona = pagosjornada.idpersona
 where pagosjornada.idpersona='$idepersona' and fechaoperacion='$dateoperation'"; 

            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo[]=$consulta_VU;
                }
                return $arreglo;
           $this->conexion->cerrar();
     }


}

function ComboSelectdDatePagos($idpersona) {
  $sql = "SELECT pagosjornada.idpersona, montoP,extrahours, fechasPagados, fechaoperacion, type, Nombre, Apellidos, Dni FROM pagosjornada
  inner join  personas on personas.idpersona = pagosjornada.idpersona
  WHERE pagosjornada.idpersona = $idpersona";

  $arreglo = array();
  if ($consulta = $this->conexion->conexion->query($sql)) {
    while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

      $arreglo[]=$consulta_VU;
    }
    return $arreglo;
    $this->conexion->cerrar();
  }
}


function Personal_jornadas_backgroundRunn(){ 
 $sql="SELECT  personaid,  FechaInicio, fechapago  FROM jornada";
 $arreglo = array();
 if ($consulta = $this->conexion->conexion->query($sql)) {
  while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

    $arreglo[]=$consulta_VU;

  }
  return $arreglo;
  $this->conexion->cerrar();
}   
}

function Update_State_estado_CuentaPersonal($personaid, $stated){

 $sql = "UPDATE personas SET EstadoCuenta = '$stated' WHERE idpersona = '$personaid'";
    if ($consulta = $this->conexion->conexion->query($sql)) {
        return 1;
        
    }else{
        return 0;
    }
}

}
?>

