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
        $query = "SELECT id, usuario, nombre_completo, rol FROM usuarios WHERE usuario = ? AND password = MD5(?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $usuario, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function ListadoUsuariosDisponibles(){
        $query = "SELECT id, nombre_completo FROM usuarios WHERE rol='usuario' AND estado='1' order by nombre_completo asc" ;
        $result = $this->conn->query($query);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    
    }
}
