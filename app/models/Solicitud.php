<?php
class Solicitud
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    
    public function crearSolicitud($usuario_id, $titulo, $fecha_solicitud, $solicitante, $prioridad, $tipo_solicitud, $descripcion, $colaborador, $comentarioColaborador)
    {
   

        $fecha_solicitud=$fecha_solicitud.' 00:00:00';
        
        $query = "CALL CrearSolicitudConWorkflow($usuario_id, '$titulo','$fecha_solicitud','$solicitante','$prioridad','$tipo_solicitud','$descripcion','$colaborador', '$comentarioColaborador')";
      
        $stmt = $this->conn->prepare($query);


        return $stmt->execute();
    }



    public function ObtenerListadoSolicitudesUsuarios($usuario_id)
    {
        $query = "SELECT id, usuario_id, titulo, prioridad, tipo_solicitud,estado FROM solicitudes
                  WHERE usuario_id='$usuario_id';";

        $result = $this->conn->query($query);

        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }

    public function DetalleSolicitud($id)
    {

        $query = "SELECT s.id, s.titulo, s.Nombre_solicitante, s.fecha_solicitud, s.prioridad, s.tipo_solicitud, t.GlosaTipoSolicitud,  s.descripcion, s.colaborador, u.nombre_completo, s.Observacion_Colaborador
            FROM solicitudes s, tiposolicitud t, usuarios u  
            WHERE s.id ='$id'
            AND s.tipo_solicitud =t.idTipoSolicitud
            AND s.colaborador=u.id;";
        
        $result = $this->conn->query($query);

        $DetalleSolicitud = [];
        while ($row = $result->fetch_assoc()) {
            $DetalleSolicitud[] = $row;
        }
        return $DetalleSolicitud;
    }

    Public function DerivarSolicitud($idSolicitud, $usuario_idActual, $prioridad, $IdActividad, $IdUsuarioDerivar, $comentarioUsuarioDerivar)
    {
        $query = "CALL DerivarSolicitud($idSolicitud, $usuario_idActual, '$prioridad',$IdActividad, $IdUsuarioDerivar, '$comentarioUsuarioDerivar')";
        /* echo $query;
        exit(); */
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }


}
