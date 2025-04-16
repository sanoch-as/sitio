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

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $idSolicitud = $row['IdSolicitud']; // Captura el ID de la solicitud generada
            } else {
                $idSolicitud = null;
            }
        } else {
            $idSolicitud = null;
        }
    
        $stmt->close();
       
        return $idSolicitud; // Retorna el ID de la solicitud creada
    }



    public function ObtenerListadoSolicitudesUsuarios($usuario_id)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        /*  $query = "SELECT sol.id, sol.usuario_id, sol.titulo, sol.prioridad, sol.tipo_solicitud, ts.GlosaTipoSolicitud, sol.estado FROM gestion_solicitudes.solicitudes sol, gestion_solicitudes.tiposolicitud ts
                WHERE sol.usuario_id='$usuario_id'
                AND sol.tipo_solicitud=ts.idTipoSolicitud ORDER BY sol.id ASC;"; */


            $query = "SELECT sol.id, sol.titulo AS titulo, sol.prioridad, ts.GlosaTipoSolicitud, sol.estado as 'estado'
                FROM gestion_solicitudes.solicitudes sol
                JOIN gestion_solicitudes.workflow wf ON sol.id = wf.IdSolicitud
                JOIN gestion_solicitudes.tiposolicitud ts ON sol.tipo_solicitud = ts.idTipoSolicitud
                WHERE  wf.IndexTareaVigenteSolicitud = '1'
                AND  sol.estado != 'completado'";
                
                if ($usuario_id != 1) { // Si el usuario no es el administrador (id 1)
                    $query .= " AND wf.idUsuarioActual = '$usuario_id' ORDER BY sol.id ASC;";
                } else {
                    $query .= " ORDER BY sol.id ASC; ";
                }


                


       

        $result = $this->conn->query($query);

        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        $result->free();
        return $solicitudes;
    }

    public function DetalleSolicitud($id)
    {        
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        $query="CALL ObtenerDetalleSolicitud($id)";    
        
        $result = $this->conn->query($query);

        $DetalleSolicitud = [];
        while ($row = $result->fetch_assoc()) {
            $DetalleSolicitud[] = $row;
        }
        $result->free();
        return $DetalleSolicitud;
    }

    public function DerivarSolicitud($idSolicitud, $usuario_idActual, $prioridad, $IdActividad, $IdUsuarioDerivar, $comentarioUsuarioDerivar)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        $query = "CALL DerivarSolicitud($idSolicitud, $usuario_idActual, '$prioridad',$IdActividad, $IdUsuarioDerivar, '$comentarioUsuarioDerivar')";
       /*   echo $query;
        exit();  */
        $stmt = $this->conn->prepare($query);
        $result=$stmt->execute();
        $stmt->close();
        return $result;
    }

    public function ObtenerNotasSolicitud($id)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        
        $query = "SELECT id, NombreUsuarioComentario, Comentario, DATE_FORMAT(FechaComentario, '%d/%m/%Y %H:%i:%s') as FechaComentario FROM comentarios
                  WHERE IdSolicitud=$id order by id asc;";

        $result = $this->conn->query($query);

        $notas = [];
        while ($row = $result->fetch_assoc()) {
            $notas[] = $row;
        }
        $result->free(); 
        return $notas;
    }


    public function ObtenerSeguimiento($id)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        
        $query = "SELECT NombreTarea as 'Actividad', 
                DATE_FORMAT(FechaDerivacionTarea, '%d/%m/%Y %H:%i:%s') as 'FechaDerivacionTarea',   
                DATE_FORMAT(FechaInicioTrabajo, '%d/%m/%Y %H:%i:%s') as 'FechaInicioTrabajo', 	
                DATE_FORMAT(FechaFinTrabajo, '%d/%m/%Y %H:%i:%s') as 'FechaFinTrabajo', 	
                NombreUsuarioActual as 'UsuarioDerivado'	
                FROM gestion_solicitudes.workflow 	
                WHERE IdSolicitud=$id
                ORDER BY IdWorkflow asc;";

        $result = $this->conn->query($query);

        $seguimientos = [];
        while ($row = $result->fetch_assoc()) {
            $seguimientos[] = $row;
        }
        $result->free(); 
        return $seguimientos;
    }



}
