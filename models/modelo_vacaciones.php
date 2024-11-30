<?php
class Vacaciones{
  private $conexion;
  function __construct(){
    require_once 'modelo_conexion.php';
    $this->conexion = new conexion();
    $this->conexion->conectar();
  }

  function Listar_Personas_Vacaciones(){

    $sql="select  idpersona,Nombre,Apellidos,Dni,Salario,diasvacacionale,stadovaciones FROM personas
    INNER JOIN jornada ON jornada.personaid = personas.idpersona ";
    $arreglo = array();
    if ($consulta = $this->conexion->conexion->query($sql)) {
      while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

        $arreglo['data'][]=$consulta_VU;

      }
      return $arreglo;
      $this->conexion->cerrar();
    }

  }
  function Data_Show_Persona($idpersona){
    $sql="SELECT  idpersona,Nombre,Apellidos,Dni,diasvacacionale FROM personas 
    INNER JOIN jornada ON jornada.personaid = personas.idpersona
    where idpersona='$idpersona' ";
    $arreglo = array();
    if ($consulta = $this->conexion->conexion->query($sql)) {
      while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

        $arreglo[]=$consulta_VU;

      }
      return $arreglo;
      $this->conexion->cerrar();
    }	
  }

  function Registrar_Vacaciones_Empleado( $idempleado,$diadisponibleactual,$fechainicio,$fechafinal,$descrition,$motivo,$diasselecionados){
   $sql = "INSERT INTO vacaciones(idempleado, fechinicio, fechafinal, motivo, diasvacaciones, descripcion,datecreate) 
   VALUES ('$idempleado','$fechainicio','$fechafinal','$motivo','$diasselecionados','$descrition',NOW())";
   if ($consulta = $this->conexion->conexion->query($sql)) {

     $this->Actualizar_Estado_Persona($idempleado,$diadisponibleactual);

     $response = array('status' => true,'auth' => true,'msg' => 'La operación se completo corectamente.','data'=> 1);
     return json_encode($response);

   }else{
    $response = array('status' => true,'auth' => true,'msg' => 'No se pudo completar el regitro','data'=> 0);
    return json_encode($response);
  }

}

function Actualizar_Estado_Persona($idempleado,$diadisponibleactual){

  $sql = "UPDATE  jornada SET diasvacacionale='$diadisponibleactual', stadovaciones='Vacaciones' WHERE personaid= '$idempleado' ";
  if ($consulta = $this->conexion->conexion->query($sql)) {      
    return 1;

  }else{
    return 0;
  }
}

function Personal_Vacaciones_backgroundRunn(){ 
 $sql="SELECT  idempleado, fechinicio, fechafinal FROM vacaciones ";
 $arreglo = array();
 if ($consulta = $this->conexion->conexion->query($sql)) {
  while ($consulta_VU = mysqli_fetch_assoc($consulta)) {

    $arreglo[]=$consulta_VU;

  }
  return $arreglo;
  $this->conexion->cerrar();
}   
}

function Listar_Empleados_Cambiar_Estado($elemt,$estado){
//cambiar esto
 $sql = "UPDATE  jornada SET  stadovaciones='$estado' WHERE personaid= '$elemt' ";
 if ($consulta = $this->conexion->conexion->query($sql)) {      
  return 1;

}else{
  return 0;
}
}

function Listar_Vacaciones(){
  $sql="select  idpersona,Nombre, Apellidos,dni, fechinicio, fechafinal, motivo, diasvacaciones,jornada.diasvacacionale FROM vacaciones
  inner join  personas on personas.idpersona = vacaciones.idempleado 
  inner join  jornada on jornada.personaid = vacaciones.idempleado";
  $arreglo = array();
  if ($consulta = $this->conexion->conexion->query($sql)) {
    while ($consulta_VU = mysqli_fetch_assoc($consulta)) {
     $arreglo['data'][]=$consulta_VU;
   }
   return $arreglo;
   $this->conexion->cerrar();
 } 
}

function Listar_Vacaciones_filters($fechainicio,$fechafin){
  $sql="select  idpersona,Nombre, Apellidos,dni, fechinicio, fechafinal, motivo, diasvacaciones,jornada.diasvacacionale FROM vacaciones
  inner join  personas on personas.idpersona = vacaciones.idempleado 
  inner join  jornada on jornada.personaid = vacaciones.idempleado 
  WHERE  (vacaciones.datecreate BETWEEN '$fechainicio' AND '$fechafin')"; 

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

