<?php 

class conexion
{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    function __construct()
    {
        $conexionDatos = $this->datosConexion();

        foreach($conexionDatos as $key => $value)
        {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }

        $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);

        if($this->conexion->connect_errno)
        {
            echo "No fue posible llevar a cabo la conexión";
            die();
        }
    }

    private function datosConexion()
    {
        $direccionArchivo = dirname(__FILE__);
        $jsondata = file_get_contents($direccionArchivo . "/" . "config");
        
        return json_decode($jsondata, true);
    }

    public function obtenerDatos($sqlstr)
    {
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();

        foreach($results as $key)
        {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }

    private function convertirUTF8($array)
    {
        array_walk_recursive($array, function(&$item, $key)
        {
            if(!mb_detect_encoding($item, 'utf-8', true))
            {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }

    public function nonQuery($sqlstr)
    {
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }

    //SE INSERTAN VALORES EN LA TABLA DE BASE DE DATOS Y SE REGRESA SU ID
    public function nonQueryId($sqlstr)
    {
        $results = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;

        if($filas >= 1)
        {
            return $this->conexion->insert_id;
        }
        else
        {
            return 0;
        }
    }


}
?>