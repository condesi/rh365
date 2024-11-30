

<?php


class Adelantos{
    private $conexion;
    function __construct(){
        require_once 'modelo_conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
    }
        



function Registrar_Adelantos($montoadelanto,$idpersona,$fechaActual,$yearActual){

   $sql = "INSERT INTO adelantos( idpersona,  Monto, Fecharegisto,yearactual) VALUES ('$idpersona','$montoadelanto','$fechaActual','$yearActual')";
          if ($consulta = $this->conexion->conexion->query($sql)) {
           $ultimoID = $this->conexion->conexion->insert_id;

         $response = array('status' => true,'auth' => true,'msg' => 'La operación se realizo con éxito.','data'=>1,'ultimoID'=>$ultimoID);
          return json_encode($response);

          }else{
             $response = array('status' => true,'auth' => true,'msg' => 'Ocurrio un error al registrar.','data'=>0,'ultimoID'=>0);
           return json_encode($response);
          }
}

function Quitar_Adelantos_Persona($idepersona,$iddelantos){
$sql=   "DELETE FROM adelantos WHERE iddelantos='$iddelantos' and idpersona ='$idepersona'";
      if ($consulta = $this->conexion->conexion->query($sql)) {

         $response = array('status' => true,'auth' => true,'msg' => 'La operacion se realizo con éxito.','data'=> 1);
        return json_encode($response);
        
      }else{
        $response = array('status' => true,'auth' => true,'msg' => 'La operacion se realizo con éxito.','data'=> 0);
        return json_encode($response);
      }

}


function Show_Adelantos_Persona($idpersona){
$sql="SELECT iddelantos, idpersona, Fecharegisto, Monto,yearactual FROM adelantos where idpersona ='$idpersona'";
     $arreglo = array();
   if ($consulta = $this->conexion->conexion->query($sql)) {
     while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
         $arreglo[]=$consulta_VU;
        }
     return $arreglo;
     $this->conexion->cerrar();
 }

}

function listar_Adelantos_Persona($idpersona){
$sql="select  adelantos.Fecharegisto, personas.Moneda, Monto,  adelantos.Estado,yearactual FROM adelantos 
INNER JOIN personas ON personas.idpersona = adelantos.idpersona 
where adelantos.idpersona ='$idpersona'";
     $arreglo = array();
   if ($consulta = $this->conexion->conexion->query($sql)) {
     while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
         $arreglo["data"][]=$consulta_VU;
        }
     return $arreglo;
     $this->conexion->cerrar();
 }

}

function Filtrar_Reportes_adelantos($inicio, $final){
$sql="select personas.idpersona, Nombre, Apellidos, Dni,Salario, Monto, adelantos.Fecharegisto, adelantos.Estado from adelantos
  inner join  personas on personas.idpersona = adelantos.idpersona 
 WHERE  (adelantos.Fecharegisto BETWEEN '$inicio' AND '$final')";

            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;
                }
                return $arreglo;
                $this->conexion->cerrar();
            }

 }

function Listar_report_adelantos(){
$sql="select personas.idpersona, Nombre, Apellidos, Dni,Salario, Monto, adelantos.Fecharegisto, adelantos.Estado from adelantos
  inner join  personas on personas.idpersona = adelantos.idpersona";

            $arreglo = array();
            if ($consulta = $this->conexion->conexion->query($sql)) {
                while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

                    $arreglo['data'][]=$consulta_VU;
                }
                return $arreglo;
                $this->conexion->cerrar();
            }

 }
 
function listar_Personas_jornadOn(){
  $sql="SELECT  idpersona, Nombre,Apellidos,Dni,Salario,resulentrevista,Estado FROM jornada
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



}
?>