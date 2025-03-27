<?php
class TipoSolicitud {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTipos() {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        $sql = "SELECT idTipoSolicitud, GlosaTipoSolicitud FROM tiposolicitud";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            $tipos = [];
            while ($row = $result->fetch_assoc()) {
                $tipos[] = $row;
            }

            // Liberar el resultado antes de hacer otra consulta
            $result->free();
            $stmt->close();
            
            return $tipos;
        } else {
            throw new Exception("Error al preparar la consulta");
        }
    }


}
?>