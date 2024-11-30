
<?php


class Dasboard{
    private $conexion;
    function __construct(){
        require_once 'modelo_conexion.php';
        $this->conexion = new conexion();
        $this->conexion->conectar();
    }
    
    function DashBoardTableroInfo() {
        $personas = 0;
        $trabajo = 0;
        $horas = 0;
        $adelantos = 0;
        $dataExtra = array();
        $dataadelant = array();

    // Consulta para obtener el número de personas
        $sqlPersonas = "SELECT COUNT(*) AS total_personas FROM personas";
        $resultPersonas = $this->conexion->conexion->query($sqlPersonas);

        if ($resultPersonas && $resultPersonas->num_rows > 0) {
            $rowPersonas = $resultPersonas->fetch_assoc();
            $personas = $rowPersonas['total_personas'];
        }

    // Consulta para obtener el número de registros en la tabla jornada
        $sqlTrabajo = "SELECT COUNT(*) AS total_trabajo FROM jornada";
        $resultTrabajo = $this->conexion->conexion->query($sqlTrabajo);

        if ($resultTrabajo && $resultTrabajo->num_rows > 0) {
            $rowTrabajo = $resultTrabajo->fetch_assoc();
            $trabajo = $rowTrabajo['total_trabajo'];
        }

   // Consulta para obtener la suma de la columna "total" en la tabla horaextra
        $sqlHoras = "SELECT SUM(total) AS total_horas FROM horaextra";
        $resultHoras = $this->conexion->conexion->query($sqlHoras);

        if ($resultHoras && $resultHoras->num_rows > 0) {
           $rowHoras = $resultHoras->fetch_assoc();
           $horas = number_format($rowHoras['total_horas'] ?? 0, 2);
       } else {
           $horas = 0;
       }


  // Consulta para obtener la suma de la columna "Monto" en la tabla adelantos
       $sqlAdelantos = "SELECT SUM(Monto) AS total_adelantos FROM adelantos";
       $resultAdelantos = $this->conexion->conexion->query($sqlAdelantos);

       if ($resultAdelantos && $resultAdelantos->num_rows > 0) {
        $rowAdelantos = $resultAdelantos->fetch_assoc();
        $adelantos = number_format($rowAdelantos['total_adelantos'] ?? 0, 2);
    } else {
        $adelantos = 0; // Valor predeterminado si no hay resultados
    }

    
    $horasextras = "SELECT SUM(hextra) AS suma_horas, DATE_FORMAT(fecharegistro, '%Y-%m') AS mes, YEAR(fecharegistro) AS anio, SUM(total) AS suma_total
    FROM horaextra
    GROUP BY anio, mes";
    $resultHorasextras = $this->conexion->conexion->query($horasextras);

    $anioAnterior = null;

    if ($resultHorasextras && $resultHorasextras->num_rows > 0) {
    // Obtener los datos de la consulta
        while ($row = $resultHorasextras->fetch_assoc()) {
            $anioActual = $row["anio"];
            $mes = $row["mes"];

        // Reiniciar el conteo de barras si hay cambio de año
            if ($anioActual !== $anioAnterior) {
                $dataExtra[] = array(
                    "mes" => $mes,
                    "valor" => $row["suma_horas"],
                    "total" => $row["suma_total"]
                );
                $anioAnterior = $anioActual;
            } else {
            // Si no hay cambio de año, agregar los datos sin reiniciar el conteo de barras
                $dataExtra[] = array(
                    "mes" => $mes,
                    "valor" => $row["suma_horas"],
                    "total" => $row["suma_total"]
                );
            }
        }
    } else {
    $dataExtra = []; // Valor predeterminado si no hay resultados
}


   // Consulta para obtener la suma de la columna "Monto" en la tabla adelantos y agrupar por año y mes
$adelantosaql = "SELECT SUM(Monto) AS sumamonto, DATE_FORMAT(Fecharegisto, '%Y-%m') AS mes, YEAR(Fecharegisto) AS anio
FROM adelantos
GROUP BY anio, mes";

$resuladelantos = $this->conexion->conexion->query($adelantosaql);

$anioAnterioadelants = null;

if ($resuladelantos && $resuladelantos->num_rows > 0) {
    // Obtener los datos de la consulta
    while ($row = $resuladelantos->fetch_assoc()) {
      $anioActual = $row["anio"];
      $mes = $row["mes"];

         // Reiniciar el conteo de barras si hay cambio de año
      if ($anioActual !== $anioAnterioadelants) {
       $dataadelant[] = array(
           "Monto" => $row["sumamonto"],
           "mes" => $mes
       );
       $anioAnterioadelants = $anioActual;
   } else {
            // Si no hay cambio de año, agregar los datos sin reiniciar el conteo de barras
       $dataadelant[] = array(
           "Monto" => $row["sumamonto"],
           "mes" => $mes
       );
   }
}
} else {
    $dataadelant = []; // Valor predeterminado si no hay resultados
}

    // Construir la respuesta en formato JSON
$response = array(
    'status' => true,
    'auth' => true,
    'msg' => 'Se encontraron los siguientes registros',
    'personas' => $personas,
    'trabajo' => $trabajo,
    'horas' => $horas,
    'adelantos' => $adelantos,
    'hextras'=>$dataExtra,
    'dataadelantos'=>$dataadelant
);

    // Retornar la respuesta en formato JSON
return json_encode($response);
}


}
?>