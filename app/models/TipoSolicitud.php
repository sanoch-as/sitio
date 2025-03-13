<?php
class TipoSolicitud {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTipos() {
        $query = "SELECT idTipoSolicitud, GlosaTipoSolicitud FROM tiposolicitud WHERE Estado='1'";
        $result = $this->conn->query($query);

        $tipos = [];
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
        return $tipos;
    }
}
?>