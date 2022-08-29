<?php 

require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

$_auth = new auth;
$_respuestas = new respuestas;

//VALIDACIÓN DEL TIPO DE REQUEST QUE SE ENVÍA
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //CON ESTO OBTENEMOS LA INFORMACIÓN ENVIADA DEL JSON
    $postBody = file_get_contents("php://input");

    //SE ENVIAN LOS DATOS AL MANEJADOR
    $datosArray = $_auth->login($postBody);

    //DEVOLVEMOS UNA RESPUESTA
    header('content-type: application/json');

    if(isset($datosArray["result"]["error_id"]))
    {
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }
    else
    {
        http_response_code(200);
    }
    echo json_encode($datosArray);
}
else
{
    header('content-type: application/json');
    $datosArray = $_respuestas->error_405();

    echo json_encode($datosArray);
}

?>