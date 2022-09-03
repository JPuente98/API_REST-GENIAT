<?php 

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class publicaciones extends conexion
{
    private $table = "publicaciones";
    private $titulo = "";
    private $descripcion = "";
    private $publicacionId = "";
    private $usuarioId = "";
    private $NombreCompleto = "";
    private $rol = "";

    public function get($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();
        $this->usuarioId = $arrayToken[0]['UsuarioId'];

        if($arrayToken)
        {
            if($arrayToken[0]['Rol'] == 2 || $arrayToken[0]['Rol'] >= 4)
            {
                if(!isset($datos['publicacionId']))
                {
                    return $_respuestas->error_400();
                }
                else
                {
                    $this->publicacionId = $datos['publicacionId'];
                    $resp = $this->obtenerPublicacion();
        
                    if($resp)
                    {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "publicacionId" => $resp
                        );
                        return $respuesta;
                    }
                    else
                    {
                        return $_respuestas->error_401("No existe publicación con ese id");
                    }
                }
            }
            else
            {
                return $_respuestas->error_401("El usuario " .$arrayToken[0]['Nombre']." " .$arrayToken[0]['Apellido']. " No cuenta con los permisos para consultar");
            }
        }
        else
        {
            return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
        }
    }

    public function obtenerPublicacion()
    {
        if($this->publicacionId == "")
        {
            $query = "SELECT * FROM " .$this->table;
        }
        else
        {
            $query = "SELECT * FROM " .$this->table. " WHERE PublicacionId = " .$this->publicacionId. "";
        }

        //print($query);
        return parent::obtenerDatos($query);
    }

    public function post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();
        $this->usuarioId = $arrayToken[0]['UsuarioId'];

        if($arrayToken)
        {
            if($arrayToken[0]['Rol'] >= 3)
            {
                if(!isset($datos['titulo']) || !isset($datos['descripcion']))
                {
                    return $_respuestas->error_400();
                }
                else
                {
                    $this->titulo = $datos['titulo'];
                    $this->descripcion = $datos['descripcion'];
                    $this->NombreCompleto = $arrayToken[0]['Nombre']." ".$arrayToken[0]['Apellido'];
                    $rol_numerico = $arrayToken[0]['Rol'];


                    switch($rol_numerico)
                    {
                        case 1:
                            $this->rol = "Básico";
                            break;

                        case 2:
                            $this->rol = "Medio";
                            break;

                        case 3:
                            $this->rol = "MedioAlto";
                            break;

                        case 4:
                            $this->rol = "AltoMedio";
                            break;

                        case 5:
                            $this->rol = "Alto";
                            break;

                    }
    
                    $resp = $this->insertarPublicacion();
        
                    if($resp)
                    {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "publicacionId" => $resp
                        );
                        return $respuesta;
                    }
                    else
                    {
                        return $_respuestas->error_500();
                    }
                }
            }
            else
            {
                return $_respuestas->error_401("El usuario " .$arrayToken[0]['Nombre']." " .$arrayToken[0]['Apellido']. " No cuenta con los permisos para publicar");
            }
        }
        else
        {
            return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
        }
    }

    private function insertarPublicacion()
    {
        $date = date("Y-m-d H:i");
        $query = "INSERT INTO ".$this->table." (UsuarioId, Titulo, Descripcion, Rol_usuario, Nombre_Completo, Fecha)
        VALUES
        ('" .$this->usuarioId. "','" .$this->titulo. "','" .$this->descripcion. "','" .$this->rol."','" .$this->NombreCompleto. "','" .$date. "')";
        
        $resp = parent::nonQueryId($query);
        
        if($resp)
        {
            return $resp;
        }
        else
        {
            return 0;
        }
        
    }

    public function put($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();

        if($arrayToken)
        {
            if($arrayToken[0]['Rol'] >= 4)
            {
                if(!isset($datos['publicacionId']))
                {
                   return $_respuestas->error_400();
                }
                else
                {
                    $this->publicacionId = $datos['publicacionId'];
                    if(isset($datos['titulo'])){$this->titulo = $datos['titulo'];} 
                    if(isset($datos['descripcion'])){$this->descripcion = $datos['descripcion'];} 
        
                    $resp = $this->modificarPublicacion();
        
                    if(!$resp)
                    {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "publicacionId" => $this->publicacionId
                        );
                        return $respuesta;
                    }
                    else
                    {
                        return $_respuestas->error_500();
                    }
                }
            }
            else
            {
                return $_respuestas->error_401("El usuario " .$arrayToken[0]['Nombre']." " .$arrayToken[0]['Apellido']. " No cuenta con los permisos para actualizar");
            }
        }
        else
        {
            return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
        }
    }

    private function modificarPublicacion()
    {
        if(!$this->titulo)
        {
            $query = "UPDATE " .$this->table. " SET Descripcion = '" .$this->descripcion. "' WHERE PublicacionId = '" .$this->publicacionId . "'";    
        }
        else if(!$this->descripcion)
        {
            $query = "UPDATE " .$this->table. " SET Titulo = '" .$this->titulo. "' WHERE PublicacionId = '" .$this->publicacionId . "'";    
        }
        else
        {
            $query = "UPDATE " .$this->table. " SET Titulo = '" .$this->titulo. "', Descripcion = '" .$this->descripcion. "' WHERE PublicacionId = '" .$this->publicacionId . "'";  
        }  
        
        $resp = parent::nonQueryId($query); 
        if($resp >= 1)
        {
            return $resp;
        }
        else
        {
            return 0;
        }
        
    }

    public function delete($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        $this->token = $datos['token'];
        $arrayToken = $this->buscarToken();

        if($arrayToken)
        {
            if($arrayToken[0]['Rol'] == 5)
            {
                if(!isset($datos['publicacionId']))
                {
                    return $_respuestas->error_400();
                }
                else
                {
                    $this->publicacionId = $datos['publicacionId'];
                    $resp = $this->eliminarPublicacion();
        
                    if($resp)
                    {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "publicacionId" => $this->publicacionId
                        );
                        return $respuesta;
                    }
                    else
                    {
                        return $_respuestas->error_500();
                    }
                }
            }
            else
            {
                return $_respuestas->error_401("El usuario " .$arrayToken[0]['Nombre']." " .$arrayToken[0]['Apellido']. " No cuenta con los permisos para eliminar");
            }
        }
        else
        {
            return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
        }
    }

    private function eliminarPublicacion()
    {
        $query = "DELETE FROM " . $this->table . " WHERE PublicacionId = '" . $this->publicacionId . "'";
        $resp = parent::nonQuery($query);

        if($resp >= 1)
        {
            return $resp;
        }
        else
        {
            return 0;
        }
        
        
    }

    private function buscarToken()
    {
        $query = "SELECT usuarios.Rol, usuarios_token.UsuarioId, usuarios.Nombre, usuarios.Apellido FROM usuarios 
        INNER JOIN usuarios_token ON usuarios.UsuarioId = usuarios_token.UsuarioId 
        WHERE usuarios_token.Token = '$this->token'";

        $resp = parent::obtenerDatos($query);

        //print_r($resp);

        if($resp)
        {
            return $resp;
        }
        else
        {
            return 0;
        }
    }

    private function actualizarToken($tokenid)
    {
        $date = date("Y-m-d H:i");
        $query =  "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        
        if($resp >= 1)
        {
            return $resp;
        }
        else
        {
            return 0;
        }
    }
}


?>


