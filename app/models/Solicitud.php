<?php
class Solicitud {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function crearSolicitud($usuario_id, $titulo, $fecha_solicitud, $solicitante, $prioridad, $tipo_solicitud, $descripcion, $colaborador, $comentarioColaborador) {
       
       
        $query = "INSERT INTO solicitudes (usuario_id, titulo, fecha_solicitud, Nombre_solicitante, prioridad, tipo_solicitud, descripcion, colaborador, Observacion_Colaborador, fecha_creacion, fecha_actualizacion) 
                  VALUES ($usuario_id, '$titulo',$fecha_solicitud,'$solicitante','$prioridad','$tipo_solicitud','$descripcion','$colaborador', '$comentarioColaborador', NOW(), NOW())";

        $stmt = $this->conn->prepare($query);
       
        
        return $stmt->execute();
    }

    public function ObtenerListadoSolicitudesUsuarios($usuario_id){
        $query = "SELECT id, usuario_id, titulo, prioridad, tipo_solicitud,estado FROM solicitudes
                  WHERE usuario_id='$usuario_id';";

        $result = $this->conn->query($query);

        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }
}
?>