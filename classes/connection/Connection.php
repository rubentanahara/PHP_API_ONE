<?php
// Clase para ejecutar la conexión con la base de datos
class Connection {
  // VARIABLES
  private $server;
  private $user;
  private $password;
  private $database;
  private $port;
  private $conn;

  // CONSTRUCTOR
  function __construct() {
    $listadosDatos = $this->initConnection();
    foreach ($listadosDatos as $key => $value) {
      $this->server = $value["server"];
      $this->user = $value["user"];
      $this->password = $value["password"];
      $this->database = $value["database"];
      $this->port = $value["port"];
    }
    
    // CREANDO CONEXION 
    // CONTROLANDO EXCEPCIONES
    try {
      $this->conn = new mysqli(
        $this->server,
        $this->user,
        $this->password,
        $this->database,
        $this->port
      );

      if ($this->conn->connect_errno) {
        throw new Exception("Error en la conexión a la base de datos.");
      }
      else {
        echo "Connected!"
      }
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      die();
    }
  }

  private function initConnection() {
    $address = dirname(__FILE__);
    $jsondata = file_get_contents($address . "/" . "config");
    return json_decode($jsondata, true);
  }
}
?>

