<?php
ini_set('display_errors', 1);
error_reporting(~0);
// Clase para ejecutar la conexión con la base de datos
class Connection
{
  // VARIABLES
  private string $server;
  private string $user;
  private string $password;
  private string $database;
  private int $port;
  private ?mysqli $conn = null; // Initialize with null

  // CONSTRUCTOR
  public function __construct()
  {
    $this->conn = new mysqli();
    $listadosDatos = $this->DataConnection(); // guardar datos de la conexión
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
      $this->conn->connect(
        $this->server,
        $this->user,
        $this->password,
        $this->database,
        $this->port
      );
      // SI ALGO SALE MAL
      if ($this->conn->connect_errno) {
        throw new Exception("Error en la conexión a la base de datos.");
      } else {
        //echo "Connected!!! \n";
      }
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      die();
    }
  }

  //Iniciando conexión
  private function DataConnection(): array
  {
    $address = __DIR__; // recibie la dirección de config
    $jsondata = file_get_contents($address . "/" . "config"); // guardar el contenido del archivo
    return json_decode($jsondata, true); // convertir a array
  }
  // Convertir texto a UTF-8
  private function ConvertToUTF8(array $arr): array
  {
    try {
      array_walk_recursive($arr, function (&$item) {
        if (!mb_detect_encoding($item, 'utf-8', true)) {
          $item = mb_convert_encoding($item, 'UTF-8');
        }
      });
      return $arr;
    } catch (Exception $e) {
      // Manejar la excepción aquí, puedes mostrar el mensaje de error o realizar alguna otra acción, como registrar el error
      echo "Error al convertir a UTF-8: " . $e->getMessage();
      return $arr; // O puedes devolver el array sin cambios o lanzar otra excepción según tus necesidades
    }
  }

  // Acciones de la API
  public function GetData(string $query): array
  {
    try {
      if ($this->conn === null) {
        throw new Exception("Connection is not initialized.");
      }

      $results = $this->conn->query($query);
      if (!$results) {
        throw new Exception($this->conn->error);
      }

      $arr = $results->fetch_all(MYSQLI_ASSOC);
      return $this->ConvertToUTF8($arr);
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return [];
    }
  }

  public function Save(string $query): int
  {
    $results = $this->conn->query($query);
    return $this->conn->affected_rows;
  }
  public function nonQueryId(string $query): int
  {
    $results = $this->conn->query($query);
    $rows = $this->conn->affected_rows;
    if ($rows > 0) {
      return $this->conn->insert_id;
    }
    return 0;
  }
  public function encriptar(string $string): string
  {
    return md5($string);
  }
}
