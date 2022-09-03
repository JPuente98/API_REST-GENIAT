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
    private $password = "";

    public function Post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['nombre']) || !isset($datos['password']) || !isset($datos['correo']) || !isset($datos['rol']) || !isset($datos['apellido']))
        {
            return $_respuestas->error_400();
        }
        else
        {
            $val = true;

            $this->Nombre = $datos['nombre'];
            $this->Apellido = $datos['apellido'];
            $this->Correo = $datos['correo'];
            $this->Rol = $datos['rol'];
            $this->password = $datos['password'];

            $tipo_variable = is_string($datos['rol']);

            if($tipo_variable)
            {
                return $_respuestas->error_401("Favor de utilizar un rol correcto, la lista es la siguiente: 1 = Básico, 2 = Medio, 3 = MedioAlto, 4 = AltoMedio, 5 = Alto");
            }
            else
            {
                if($datos['rol'] >= 1 && $datos['rol'] <= 5)
                {
                    $respuesta = $this->insertarUsuarios();
    
                    if($respuesta)
                    {
                        return "Se ah registrado el usuario de manera correcta.";
                    }
                    else
                    {
                        return $_respuestas->error_500();
                    }
                }
                else
                {
                    return $_respuestas->error_401("Favor de utilizar un rol correcto, la lista es la siguiente: 1 = Básico, 2 = Medio, 3 = MedioAlto, 4 = AltoMedio, 5 = Alto");
                }
            }
        }
    }

    private function insertarUsuarios()
    {
        $query = "INSERT INTO ". $this->table . " (Nombre, Apellido, Correo, Password, Rol)
        VALUES
        ('" .$this->Nombre. "','" .$this->Apellido. "','".$this->Correo."','".$this->password."','".$this->Rol."')";

        $respuesta = parent::nonQueryId($query);

        if($respuesta)
        {
            return $respuesta;
        }
        else
        {
            return 0;
        }
    }
}


?>