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
     /* else {
        echo "Connected";
      }*/
      
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      die();
    }
  }
  //Iniciando conexión
  private function initConnection() {
    $address = dirname(__FILE__);
    $jsondata = file_get_contents($address . "/" . "config");
    return json_decode($jsondata, true);
  }
  // Convertir texto a UTF8 
  private function ConvertToUTF8($arr){
    array_walk_recursive($arr,function(&$item){
      if(!mb_detect_encoding($item,'utf-8',true)) {
          $item = utf_encode($item); 
      }
    });
    return $arr;
  }

  // Acciones de la API
  public function GetData($query){
    $results = $this->conn->query($query);
    $arr = array();
    foreach ($results as $key) {
      $arr[] = $key;
    }
    return $this->ConvertToUTF8($arr);
  }

  public function nonQuery($query) {
    $results = $this->conn->query($query);
    return $this->conn->affected_rows;
  }

  public function nonQueryId($query) {
    $results = $this->conn->query($query);
    $rows = $this->conn-> affected_rows;
    if(rows>0){
      return $this->conn->insert_id;
    }
    return 0;
  }
}
?>

