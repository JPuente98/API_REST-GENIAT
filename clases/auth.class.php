<?php 
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';


class auth extends conexion
{
    public function login($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if(!isset($datos['correo']) || !isset($datos['password']))
        {
            //ERROR CON LOS CAMPOS
            return $_respuestas->error_400();
        }
        else
        {
            //TODO BIEN
            $correo = $datos['correo'];
            $password = $datos['password'];
            $datos = $this->obtenerDatosUsuario($correo);

            if($datos)
            {
                //VARIFICA SI LA CONTRASEÑA ES IGUAL
                if($password == $datos[0]['Password'])
                {

                    //CREACIÓN DEL TOKEN
                    $verificar = $this->insertarToken($datos[0]['Correo']);

                    if($verificar)
                    {
                        $result = $_respuestas->response;
                        $result["result"] = array(
                            "token" => $verificar
                        );
                        return $result;
                    }   
                    else
                    {
                        //ERROR AL GUARDAR
                        return $_respuestas->error_500("Error interno, no hemos podido guardar");
                    }
                }   
                else
                {
                    //LA CONTRASEÑA NO ES IGUAL
                    return $_respuestas->error_200("El password es invalido");
                }
            }
            else
            {
                return $_respuestas->error_200("El usuario con correo $correo no existe");
            }
        }
    }

    private function obtenerDatosUsuario($correo)
    {
        $query = "SELECT UsuarioId, Nombre, Apellido, Password, Correo FROM usuarios WHERE Correo = '$correo'";

        //HEREDANDO UN METÓDO DE LA CLASE PADRE QUE ES CONEXIÓN
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]['UsuarioId']))
        {
            return $datos;
        }
        else
        {
            return 0;
        }
    }

    private function insertarToken($correo)
    {
        $val = true;

        //COMBINACIÓN DE DOS FUNCIONES DE PHP PARA GENERAR EL TOKEN CON VALORES RANDOM HEXADECIMALES
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES('$correo', '$token', '$estado', '$date')";
        $verificar = parent::nonQuery($query);

        if($verificar)
        {
            return $token;
        }
        else
        {
            return 0;
        }
    }
}


?>