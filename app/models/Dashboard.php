<?php
class Dashboard
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function ObtenerCantidadPrioridadSolicitudesDB($usuario_id)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }

        // Consulta para obtener la cantidad de solicitudes por tipo
        $query = "SELECT sol.prioridad as 'prioridad', count(wf.IdSolicitud) as 'cantidad' 
            FROM gestion_solicitudes.solicitudes sol
            JOIN gestion_solicitudes.workflow wf ON sol.id = wf.IdSolicitud
            JOIN gestion_solicitudes.tiposolicitud ts ON sol.tipo_solicitud = ts.idTipoSolicitud
            WHERE wf.IndexTareaVigenteSolicitud = 1
            AND sol.estado != 'completado' ";

        if ($usuario_id != 1) { // Si el usuario no es el administrador (id 1)
            $query .= " AND wf.idUsuarioActual = '$usuario_id' 
                        GROUP BY sol.prioridad";
        } else {
            $query .= " GROUP BY sol.prioridad"; 
        }

            

        $result = $this->conn->query($query);

        $cantidadesTipoSol = [];

        while ($row = $result->fetch_assoc()) {
            $Solprioridad = $row['prioridad'];

            // Consulta para obtener los detalles de cada tipo de solicitud
            $detalleQuery = "SELECT sol.id as 'id', sol.titulo AS NombreSolicitud, ts.GlosaTipoSolicitud, sol.prioridad, wf.EstadoSolicitud, sol.fecha_creacion 
                FROM gestion_solicitudes.solicitudes sol
                JOIN gestion_solicitudes.workflow wf ON sol.id = wf.IdSolicitud
                JOIN gestion_solicitudes.tiposolicitud ts ON sol.tipo_solicitud = ts.idTipoSolicitud
                WHERE sol.prioridad = '$Solprioridad'
                AND wf.IndexTareaVigenteSolicitud = 1";

            if ($usuario_id != 1) { // Si el usuario no es el administrador (id 1)
                $detalleQuery .= " AND wf.idUsuarioActual = '$usuario_id'";    
            }

            

            $detalleResult = $this->conn->query($detalleQuery);

            $detalles = [];
            while ($detalleRow = $detalleResult->fetch_assoc()) {
                $detalles[] = [
                    'id' => $detalleRow['id'],
                    'NombreSolicitud' => $detalleRow['NombreSolicitud'],
                    'GlosaTipoSolicitud' => $detalleRow['GlosaTipoSolicitud'],
                    'Prioridad' => $detalleRow['prioridad'],
                    'EstadoSolicitud' => $detalleRow['EstadoSolicitud'],
                    'FechaSolicitud' => $detalleRow['fecha_creacion']
                ];
            }
            $detalleResult->free();

            // Agregar los detalles a la estructura de la respuesta
            $row['detalle'] = $detalles;
            $cantidadesTipoSol[] = $row;
        }

        $result->free();
        return $cantidadesTipoSol;
    }

    public function ObtenerCantidadTipoSolicitudesDB($usuario_id)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }

        // Consulta para obtener la cantidad de solicitudes por tipo
        $query = "SELECT ts.IdTipoSolicitud as 'TipoSolicitud', ts.GlosaTipoSolicitud as 'GlosaTipoSolicitud', count(wf.IdSolicitud) as 'cantidad' 
            FROM gestion_solicitudes.solicitudes sol
            JOIN gestion_solicitudes.workflow wf ON sol.id = wf.IdSolicitud
            JOIN gestion_solicitudes.tiposolicitud ts ON sol.tipo_solicitud = ts.idTipoSolicitud
            WHERE wf.IndexTareaVigenteSolicitud = 1
            AND sol.estado != 'completado'";

        if ($usuario_id != 1) { // Si el usuario no es el administrador (id 1)
            $query .= " AND wf.idUsuarioActual = '$usuario_id' 
            GROUP BY ts.GlosaTipoSolicitud";
        } else {
            $query .= " GROUP BY ts.GlosaTipoSolicitud"; 
        }


        $result = $this->conn->query($query);

        $cantidadesTipoSol = [];

        while ($row = $result->fetch_assoc()) {
            $tipoSolicitud = $row['TipoSolicitud'];

            // Consulta para obtener los detalles de cada tipo de solicitud
            $detalleQuery = "SELECT sol.id as 'id', sol.titulo AS NombreSolicitud, ts.GlosaTipoSolicitud, sol.prioridad, wf.EstadoSolicitud, sol.fecha_creacion 
                FROM gestion_solicitudes.solicitudes sol
                JOIN gestion_solicitudes.workflow wf ON sol.id = wf.IdSolicitud
                JOIN gestion_solicitudes.tiposolicitud ts ON sol.tipo_solicitud = ts.idTipoSolicitud
                WHERE ts.IdTipoSolicitud = '$tipoSolicitud'
                AND wf.IndexTareaVigenteSolicitud = 1";
                if ($usuario_id != 1) { // Si el usuario no es el administrador (id 1)
                    $detalleQuery .= " AND wf.idUsuarioActual = '$usuario_id'";    
                }
                

            $detalleResult = $this->conn->query($detalleQuery);

            $detalles = [];
            while ($detalleRow = $detalleResult->fetch_assoc()) {
                $detalles[] = [
                    'id' => $detalleRow['id'],
                    'NombreSolicitud' => $detalleRow['NombreSolicitud'],
                    'GlosaTipoSolicitud' => $detalleRow['GlosaTipoSolicitud'],
                    'Prioridad' => $detalleRow['prioridad'],
                    'EstadoSolicitud' => $detalleRow['EstadoSolicitud'],
                    'FechaSolicitud' => $detalleRow['fecha_creacion']
                ];
            }
            $detalleResult->free();

            // Agregar los detalles a la estructura de la respuesta
            $row['detalle'] = $detalles;
            $cantidadesTipoSol[] = $row;
        }

        $result->free();
        return $cantidadesTipoSol;
    }


    public function ObtenerTarjetas($usuario_id){
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }

        // Consulta para obtener la cantidad de solicitudes asignadas
        $cantidadquery = "SELECT count(*) as 'cantidad'  
            FROM gestion_solicitudes.workflow 
            WHERE IndexTareaVigenteSolicitud = 1
            AND estadoSolicitud != 'completado'
            AND estadoSolicitud != 'rechazado'
            AND estadoSolicitud != 'anulado' ";
            
        if ($usuario_id != 1) { // Si el usuario no es el administrador (id 1)
            $cantidadquery .= " AND idUsuarioActual = '$usuario_id'";   
        } 

        $result = $this->conn->query($cantidadquery);

        $cantidadesSol = [];

        while ($row = $result->fetch_assoc()) {
            $cantidad= $row['cantidad'];

        }

        $queryHoy = "SELECT COUNT(*) AS 'hoy' 
            FROM gestion_solicitudes.workflow 
            WHERE IndexTareaVigenteSolicitud = 1
            AND FechaDerivacionTarea >= CURDATE()
            AND FechaDerivacionTarea < CURDATE() + INTERVAL 1 DAY
            AND idUsuarioActual =  '$usuario_id'";

        $resultHoy = $this->conn->query($queryHoy);

        while ($row = $resultHoy->fetch_assoc()) {
            $solicitudesHoy= $row['hoy'];

        }


        $queryUsuarioCreador = "SELECT count(*) as 'usuariocreador' 
            FROM gestion_solicitudes.solicitudes 
            WHERE usuario_id= '$usuario_id'";

        $resultUsuario = $this->conn->query($queryUsuarioCreador);

        while ($row = $resultUsuario->fetch_assoc()) {
            $solicitudesUsuarioCreador= $row['usuariocreador'];

        }

        $campos = [
            'cantidad' => $cantidad,
            'solicitudesHoy' => $solicitudesHoy,
            'solicitudesUsuarioCreador' => $solicitudesUsuarioCreador
        ];
        $cantidadesSol[] = $campos;
        $result->free();
        $resultHoy->free();
        $resultUsuario->free();     

        return $cantidadesSol;

    }
}
