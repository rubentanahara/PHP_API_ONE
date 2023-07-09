<?php
require_once 'classes/Auth.class.php';
require_once 'classes/ResponseHandler.class.php';

$_auth = new Auth;
$_responseHandler = new ResponseHandler;
echo 'Auth ';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Recibir datos
  $postBody = file_get_contents("php://input");

  // Enviar los datos al manejador
  $datosArray = $_auth->login($postBody);

  // Devolver una respuesta
  header('Content-Type: application/json');
  if (isset($datosArray["result"]["error_id"])) {
    $responseCode = $datosArray["result"]["error_id"];
    http_response_code($responseCode);
  } else {
    http_response_code(200);
  }
  echo json_encode($datosArray);
} else {
  header('Content-Type: application/json');
  $datosArray = $_responseHandler->errorMethodNotAllowed();
  http_response_code(405);
  echo json_encode($datosArray);
}
