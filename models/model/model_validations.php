<?php 

class Validations {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

   /* public function isDuplicate($table, $column, $value) {
        $sql = "SELECT 1 FROM $table WHERE $column = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
*/
    // En la clase Validations


//isDuplicateUpdate('nombre_de_la_tabla', 'nombre_de_la_columna', 'valor_a_verificar', 'ID_a_excluir', 'nombre_de_la_columna_a_excluir');

public function isDuplicate($table, $column, $value, $excludeId = null, $columnToExclude = 'id') {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
    
    if ($excludeId !== null) {
        $sql .= " AND $columnToExclude != ?";
    }

    $stmt = $this->conexion->conexion->prepare($sql);

    if ($excludeId !== null) {
        $stmt->bind_param("si", $value, $excludeId);
    } else {
        $stmt->bind_param("s", $value);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['count'] > 0;
}

//$this->recordExists('tabla', 'columna', $valor, 'id_por defecto')
public function recordExists($table, $column, $value, $idColumn = 'id') {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
    $stmt = $this->conexion->conexion->prepare($sql);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}



}


  ?>