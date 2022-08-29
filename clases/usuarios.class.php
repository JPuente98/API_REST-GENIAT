<?php 

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class usuarios extends conexion
{
    private $table = "usuarios";
    private $Nombre = "";
    private $Apellido = "";
    private $Correo = "";
    private $Rol = "";

    public function Post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['nombre']) || !isset($datos['apellido']) || !isset($datos['correo']) || !isset($datos['rol']))
        {
            return $_respuestas->error_400();
        }
        else
        {
            $val = true;
            $password = uniqid();

            $this->Nombre = $datos['nombre'];
            $this->Apellido = $datos['apellido'];
            $this->Correo = $datos['correo'];
            $this->Rol = $datos['rol'];

            $respuesta = $this->insertarUsuarios($password);

            if($respuesta)
            {
                return "Su contraseña es: ". $password;
            }
            else
            {
                return $_respuestas->error_500();
            }
            
        }
    }

    private function insertarUsuarios($password)
    {
        $query = "INSERT INTO ". $this->table . " (Nombre, Apellido, Correo, Password, Rol)
        VALUES
        ('" .$this->Nombre. "','" .$this->Apellido. "','".$this->Correo."','".$password."','".$this->Rol."')";

        $respuesta = parent::nonQueryId($query);

        if($respuesta)
        {
            return $respuesta;
        }
        else
        {
            return 0;
        }

        //print_r($query);
    }
}


?>