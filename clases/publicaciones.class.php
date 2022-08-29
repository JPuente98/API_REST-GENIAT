<?php 

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class publicaciones extends conexion
{
    private $table = "publicaciones";
    private $titulo = "";
    private $descripcion = "";

    public function listaPublicaciones($pagina = 1)
    {
        $inicio = 0;
        $cantidad = 50;

        if($pagina > 1)
        {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT PublicacionId, UsuarioId, Titulo, Descripcion FROM " . $this->table . " limit $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerPublicacion($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE PublicacionId = '$id'";
        return parent::obtenerDatos($query);
    }

    public function post($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['token']))
        {
            return $_respuestas->error_401();
        }
        else
        {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if(!isset($datos['Titulo']) || !isset($datos['Descripcion']))
                {
                    return $_respuestas->error_400();
                }
                else
                {
                    $this->titulo = $datos['Titulo'];
                    $this->descripcion = $datos['Descripcion'];

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

            /*if($arrayToken)
            {
                
            }
            else
            {
                return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
            }
            */
        }

       
    }

    private function insertarPublicacion()
    {
        $query = "INSERT INTO ".$this->table." (Titulo, Descripcion)
        VALUES
        ('". $this->titulo . "','" . $this->descripcion . "')";
        
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

        if(!isset($datos['token']))
        {
            return $_respuestas->error_401();
        }
        else
        {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if(!isset($datos['publicacionId']))
            {
                return $_respuestas->error_400();
            }
            else
            {
                $this->publicacionId = $datos['publicacionId'];
                if(isset($datos['titulo'])){ $this->titulo; }
                if(isset($datos['descripcion'])){ $this->descripcion; }

                $resp = $this->modificarPublicacion();
    
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

            /*if($arrayToken)
            {

            }
            else
            {
                return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
            }
            */
        }
    }

    private function modificarPublicacion()
    {
        $query = "UPDATE " .$this->table. " SET Titulo = '" .$this->titulo. "', Descripcion = '" .$this->descripcion. "' WHERE PublicacionId = '" .$this->publicacionId . "'";    
        $resp = parent::nonQueryId($query); 
        print_r($query);
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

        if(!isset($datos['token']))
        {
            return $_respuestas->error_401();
        }
        else
        {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken)
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
                return $_respuestas->error_401("El Token que envio, es invalido o ha caducado");
            }
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
        $query = "SELECT TokenId, UsuarioId, Estado FROM usuarios_token WHERE Token = '" .  $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);


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