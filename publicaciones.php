<?php 

require_once 'clases/respuestas.class.php';
require_once 'clases/publicaciones.class.php';

$_respuestas = new respuestas;
$_publicaciones = new publicaciones;

if($_SERVER['REQUEST_METHOD'] == "GET")
{
     //RECIBIMOS LOS DATOS ENVIADOS
     $postBody = file_get_contents("php://input");

     //ENVIAMOS LOS DATOS AL MANEJADOR
     $datosArray = $_publicaciones->get($postBody);
     
     //DEVOLVEMOS UNA RESPUESTA
     header("Content-Type: application/json");
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


    /*if(isset($_GET["page"]))
    {
        $pagina = $_GET["page"];
        $listaPublicaciones = $_publicaciones->listaPublicaciones($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaPublicaciones);
        http_response_code(200);
    }
    else if(isset($_GET["id"]))
    {
        $publicacionId = $_GET["id"];
        $datosPublicacion = $_publicaciones->obtenerPublicacion($publicacionId);
        header("Content-Type: application/json");
        echo json_encode($datosPublicacion);
        http_response_code(200);
    }*/
}
else if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //RECIBIMOS LOS DATOS ENVIADOS
    $postBody = file_get_contents("php://input");

    //ENVIAMOS LOS DATOS AL MANEJADOR
    $datosArray = $_publicaciones->post($postBody);
    
    //DEVOLVEMOS UNA RESPUESTA
    header("Content-Type: application/json");
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
else if($_SERVER['REQUEST_METHOD'] == "PUT")
{
    //RECIBIMOS LOS DATOS ENVIADOS
    $postBody = file_get_contents("php://input");

    //ENVIAMOS DATOS AL MANEJADOR 
    $datosArray = $_publicaciones->put($postBody);

    //DEVOLVEMOS UNA RESPUESTA
    header("Content-Type: application/json");
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
else if($_SERVER['REQUEST_METHOD'] == "DELETE")
{
       //RECIBIMOS LOS DATOS ENVIADOS
       $postBody = file_get_contents("php://input");

       //ENVIAMOS DATOS AL MANEJADOR 
       $datosArray = $_publicaciones->delete($postBody);
   
       //DEVOLVEMOS UNA RESPUESTA
       header("Content-Type: application/json");
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