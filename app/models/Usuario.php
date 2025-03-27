<?php
class Usuario
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function obtenerUsuarioPorCredenciales($usuario, $password)
    {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        $query = "SELECT id, usuario, nombre_completo, rol FROM usuarios WHERE usuario = ? AND password = MD5(?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $usuario, $password);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return null;
        }
        $usuario = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $usuario;
    }

    /* public function ListadoUsuariosDisponibles(){
        $query = "SELECT id, nombre_completo FROM usuarios WHERE rol in('usuario','supervisor') AND estado='1' order by nombre_completo asc" ;
        $result = $this->conn->query($query);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        $result->free();
        return $usuarios;
    
    } */

    public function ListadoUsuariosDisponibles() {
        while ($this->conn->more_results()) {
            $this->conn->next_result();
        }
        
        $query = "SELECT id, nombre_completo FROM usuarios WHERE rol in('usuario','supervisor') AND estado='1' order by nombre_completo asc" ;
        $stmt = $this->conn->prepare($query);
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }

            // Liberar el resultado y cerrar el statement
            $result->free();
            $stmt->close();
            
            return $usuarios;
        } else {
            throw new Exception("Error al preparar la consulta");
        }
    }



}
