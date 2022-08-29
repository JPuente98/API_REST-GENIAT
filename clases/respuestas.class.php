<?php 

class respuestas
{
    public $response = [
        'status' => "ok",
        "result" => array()
    ];

    public function error_405()
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "405",
            "error_msg" => "Metodo no permitido"
        );
        return $this->response;
    }

    public function error_200($msg = "Datos incorrectos")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "200",
            "error_msg" => $msg
        );
        return $this->response;
    }

    public function error_400()
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "400",
            "error_msg" => "Datos enviados incompletos, o con formato incorrecto"
        );
        return $this->response;
    }

    public function error_500($msg = "Error interno del servidor")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "500",
            "error_msg" => $msg
        );
        return $this->response;
    }

    public function error_401($msg = "No autorizado, Token inválido")
    {
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => "401",
            "error_msg" => $msg
        );
        return $this->response;
    }
}

?>