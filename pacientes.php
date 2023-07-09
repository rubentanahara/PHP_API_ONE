<?php
require_once 'classes/ResponseHandler.class.php';
require_once 'classes/Pacientes.class.php';

$responseHandler = new ResponseHandler();
$pacientes = new Pacientes();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === "GET") {
   if (isset($_GET["page"])) {
      $page = $_GET["page"];
      $pacientesList = $pacientes->ListaPacientes($page);
      echo json_encode($pacientesList);
   } else if (isset($_GET["id"])) {
      $pacienteId = $_GET["id"];
      $paciente = $pacientes->ObtenerPaciente($pacienteId);
      if (!empty($paciente)) {
         http_response_code(200);
         echo json_encode($paciente);
      }
   }
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
   $postBody = file_get_contents("php://input");
   $response = $pacientes->Post($postBody);
   if (isset($response["result"]["error_id"])) {
      $responseCode  = $response["result"]["error_id"];
      http_response_code($responseCode);
   } else {
      http_response_code(200);
   }
   echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] === "PUT") {
   $postBody = file_get_contents("php://input");
   $response = $pacientes->Put($postBody);
   if (isset($response["result"]["error_id"])) {
      $responseCode  = $response["result"]["error_id"];
      http_response_code($responseCode);
   } else {
      http_response_code(200);
   }
   echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
   $postBody = file_get_contents("php://input");
   $response = $pacientes->Delete($postBody);
   if (isset($response["result"]["error_id"])) {
      $responseCode  = $response["result"]["error_id"];
      http_response_code($responseCode);
   } else {
      http_response_code(200);
   }
   echo json_encode($response);
} else {
   echo $_SERVER['REQUEST_METHOD'];
   $datosArray = $responseHandler->errorMethodNotAllowed();
   http_response_code(405);
   echo json_encode($datosArray);
}
