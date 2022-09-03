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

                    $id_usuario = $datos[0]['UsuarioId'];
                    $WebToken = conexion::jwt($correo, $id_usuario);
                    $verificar = $this->insertarToken($id_usuario, $WebToken);

                    if($verificar == 0 || $verificar == 1)
                    {
                        $result = $_respuestas->response;
                        $result["result"] = array(
                            "token" => $WebToken
                        );
                        return $result;
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
        if(isset($datos[0]['Correo']))
        {
            return $datos;
        }
        else
        {
            return 0;
        }
    }

    private function insertarToken($id_usuario, $token)
    {
        $val = true;

        $date = date("Y-m-d H:i");
        $estado = "Activo";

        $BuscaToken = "SELECT Token FROM usuarios_token WHERE UsuarioId = '$id_usuario'";
        $ValidaToken = parent::nonQuery($BuscaToken);

        if($ValidaToken == 0)
        {
            $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES('$id_usuario', '$token', '$estado', '$date')";
        }
        else
        {
            $query = "UPDATE usuarios_token SET Token = '$token', Estado = '$estado', Fecha = '$date' WHERE UsuarioId = '$id_usuario'";
        }

        $verificar = parent::nonQuery($query);

        return $ValidaToken;
        
    }
}


?>