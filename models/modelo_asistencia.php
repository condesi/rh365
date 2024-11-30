<?php
    class Asistensia{
        private $conexion;
        function __construct(){
            require_once 'modelo_conexion.php';
            $this->conexion = new conexion();
            $this->conexion->conectar();
        }



//aqui la fugada

function listar_Personas_Asistencia_Jornadas(){
$sql=  "SELECT  idpersona, Nombre,Apellidos,Dni from personas where statedinicio='On'";
        $arreglo = array();
        if ($consulta = $this->conexion->conexion->query($sql)) {
            while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                $arreglo["data"][]=$consulta_VU;

            }
            return $arreglo;
            $this->conexion->cerrar();
        }

}
function listar_Personas_Asistencia_Show_On($date){
  $sql=  "SELECT idpersona, Nombre,Apellidos,Dni,stated FROM asistencia
INNER JOIN personas ON personas.idpersona = asistencia.personaid
where Fechas ='$date'";

        $arreglo = array();
        if ($consulta = $this->conexion->conexion->query($sql)) {
            while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                $arreglo["data"][]=$consulta_VU;

            }
            return $arreglo;
            $this->conexion->cerrar();
        }
}



function verificar_Asistencia($fechas){
 $sql = "SELECT Fechas FROM asistencia where Fechas='$fechas'  ";
    $arreglo = array();

    if ($consulta = $this->conexion->conexion->query($sql)) {
        while ($consulta_VU = mysqli_fetch_array($consulta)) {
            $arreglo[] = $consulta_VU;
        }
        return count($arreglo);
        $this->conexion->cerrar();
    }

}

function Registro_Asistencia($IdPersona,$fechas,$date){

  $sql="INSERT INTO asistencia(personaid, Fechas, stated, yearid) VALUES 
                            ('$IdPersona','$fechas','1','$date')";
  if ($consulta = $this->conexion->conexion->query($sql)) {

   $response = array('status' => true,'auth' => true,'msg' => 'La asistencia para la fecha: '.$fechas.' se registro corectamente','data'=>1);
   return json_encode($response);

 }else{
  $error = $this->conexion->conexion->error; // Obtener el mensaje de error
  $response = array('status' => true,'auth' => true,'msg' => 'La asistencia para la fecha: '.$fechas.' No se registro.Error:'.$error,'data'=>0);
   return json_encode($response);
}


}

function Resetear_Asistencia_date($fechas,$date){

$sql=   "DELETE FROM asistencia WHERE Fechas ='$fechas' and yearid = '$date'";
      if ($consulta = $this->conexion->conexion->query($sql)) {

        
    return 1;
        
      }else{
        
    return 0;
      }


}


function Filtrar_Reportes_asistencia($fechaInicio,$fechaFin){
  $sql="select  idpersona,Nombre, Apellidos,dni,Fechas,stated,yearid  FROM asistencia
   inner join  personas on personas.idpersona = asistencia.personaid 
 WHERE  (Fechas BETWEEN '$fechaInicio' AND '$fechaFin')"; 

            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;
                }
                return $arreglo;
                $this->conexion->cerrar();
            }

}

function Listar_report_asistencia(){

$sql="select  idpersona,Nombre, Apellidos,dni,Fechas,stated,yearid  FROM asistencia
   inner join  personas on personas.idpersona = asistencia.personaid "; 

            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;
                }
                return $arreglo;
                $this->conexion->cerrar();
            }


}





    }
?>
