<?php
ini_set('display_errors', 1);
error_reporting(~0);
require_once "connection/Connection.php";
require_once "ResponseHandler.class.php";

class Pacientes extends Connection
{
   private  $table = "pacientes";
   private $pacienteid = "";
   private $dni = "";
   private $correo = "";
   private $nombre = "";
   private $direccion = "";
   private $codigoPostal = "";
   private $genero = "";
   private $telefono = "";
   private $fechaNacimiento = "0000-00-00";
   private $token = "";
   //4fe3a98dcdcb4417dfd6fa2e6180084d
   public function ListaPacientes(int $pagina = 1): array
   {
      try {
         $inicio  = 0;
         $cantidad = 100;
         if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
         }
         $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " . $this->table . " limit $inicio,$cantidad";
         $datos = parent::GetData($query);
         return ($datos);
      } catch (Exception $ex) {
         echo $ex;
      }
      return [];
   }
   public function ObtenerPaciente(string $id): array
   {
      $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
      return parent::GetData($query);
   }
   public function Post(string $json)
   {
      $_responseHandler = new  ResponseHandler;
      $data = json_decode($json, true);

      if (empty($data)) {
         return $_responseHandler->errorInvalidData("No se encontraron datos válidos en el JSON.");
      }
      if (!isset($data["token"])) {
         return $_responseHandler->errorUnauthorized();
      }
      $this->token = $data["token"];
      $arrayToken =   $this->buscarToken();

      if (!$arrayToken) {
         return $_responseHandler->errorUnauthorized("El Token que envio es invalido o ha caducado");
      }
      if (!isset($data['nombre']) || !isset($data['dni']) || !isset($data['correo'])) {
         return $_responseHandler->errorIncompleteOrInvalidFormat();
      }

      $this->nombre =  $data['nombre'];
      $this->dni = $data['dni'];
      $this->correo = $data['correo'];

      if (isset($data['telefono'])) {
         $this->telefono = $data['telefono'];
      }
      if (isset($data['direccion'])) {
         $this->direccion = $data['direccion'];
      }
      if (isset($data['codigoPostal'])) {
         $this->codigoPostal = $data['codigoPostal'];
      }
      if (isset($data['genero'])) {
         $this->genero = $data['genero'];
      }
      if (isset($data['fechaNacimiento'])) {
         $this->fechaNacimiento = $data['fechaNacimiento'];
      }
      $resp = $this->insertarPaciente();
      if ($resp) {
         $respuesta = $_responseHandler->GetResponse();
         $respuesta["result"] = array(
            "pacienteId" => $resp
         );
         return $respuesta;
      } else {
         return $_responseHandler->errorInternalServerError();
      }
   }
   public function Put(string $json)
   {
      $_responseHandler = new  ResponseHandler;
      $data = json_decode($json, true);

      if (empty($data)) {
         return $_responseHandler->errorInvalidData("No se encontraron datos válidos en el JSON.");
      }
      if (!isset($data["token"])) {
         return $_responseHandler->errorUnauthorized();
      }
      $this->token = $data["token"];
      $arrayToken =   $this->buscarToken();

      if (!$arrayToken) {
         return $_responseHandler->errorUnauthorized("El Token que envio es invalido o ha caducado");
      }
      if (!isset($data["pacienteId"])) {
         return $_responseHandler->errorInvalidData("No se proporciono el identificador del paciente");
      }
      $this->pacienteid =  $data['pacienteId'];

      if (isset($data['nombre'])) {
         $this->nombre = $data['nombre'];
      }
      if (isset($data['dni'])) {
         $this->dni = $data['dni'];
      }
      if (isset($data['correo'])) {
         $this->correo = $data['correo'];
      }
      if (isset($data['telefono'])) {
         $this->telefono = $data['telefono'];
      }
      if (isset($data['direccion'])) {
         $this->direccion = $data['direccion'];
      }
      if (isset($data['codigoPostal'])) {
         $this->codigoPostal = $data['codigoPostal'];
      }
      if (isset($data['genero'])) {
         $this->genero = $data['genero'];
      }
      if (isset($data['fechaNacimiento'])) {
         $this->fechaNacimiento = $data['fechaNacimiento'];
      }
      $resp = $this->modificarPaciente();
      if ($resp) {
         $respuesta = $_responseHandler->GetResponse();
         $respuesta["result"] = array(
            "pacienteId" => $this->pacienteid
         );
         return $respuesta;
      } else {
         return $_responseHandler->errorInternalServerError();
      }
   }
   public function Delete(string $json)
   {
      $_responseHandler = new  ResponseHandler;
      $data = json_decode($json, true);

      if (empty($data)) {
         return $_responseHandler->errorInvalidData("No se encontraron datos válidos en el JSON.");
      }
      if (!isset($data["token"])) {
         return $_responseHandler->errorUnauthorized();
      }
      $this->token = $data["token"];
      $arrayToken =   $this->buscarToken();

      if (!$arrayToken) {
         return $_responseHandler->errorUnauthorized("El Token que envio es invalido o ha caducado");
      }
      if (!isset($data["pacienteId"])) {
         return $_responseHandler->errorInvalidData("No se proporciono el identificador del paciente");
      }

      $this->pacienteid =  $data['pacienteId'];

      $resp = $this->eliminarPaciente();
      if ($resp) {
         $respuesta = $_responseHandler->GetResponse();
         $respuesta["result"] = array(
            "pacienteId" => $this->pacienteid
         );
         return $respuesta;
      } else {
         return $_responseHandler->errorInternalServerError();
      }
   }
   private function insertarPaciente()
   {
      $query = "INSERT INTO " . $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo)
        values
        ('" . $this->dni . "','" . $this->nombre . "','" . $this->direccion . "','" . $this->codigoPostal . "','"  . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "')";
      $resp = parent::nonQueryId($query);
      if ($resp) {
         return $resp;
      } else {
         return 0;
      }
   }
   private function modificarPaciente()
   {
      $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->nombre . "',Direccion = '" . $this->direccion . "', DNI = '" . $this->dni . "', CodigoPostal = '" .
         $this->codigoPostal . "', Telefono = '" . $this->telefono . "', Genero = '" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', Correo = '" . $this->correo .
         "' WHERE PacienteId = '" . $this->pacienteid . "'";
      $resp = parent::Save($query);
      if ($resp > 0) {
         return $resp;
      } else {
         return 0;
      }
   }
   private function eliminarPaciente()
   {
      $query = "DELETE FROM " . $this->table . " WHERE PacienteId= '" . $this->pacienteid . "'";
      $resp = parent::Save($query);
      if ($resp > 0) {
         return $resp;
      } else {
         return 0;
      }
   }
   private function buscarToken()
   {
      $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
      $resp = parent::GetData($query);
      if ($resp) {
         return $resp;
      } else {
         return 0;
      }
   }

   private function actualizarToken($tokenid)
   {
      $date = date("Y-m-d H:i");
      $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
      $resp = parent::Save($query);
      if ($resp > 0) {
         return $resp;
      } else {
         return 0;
      }
   }
}
