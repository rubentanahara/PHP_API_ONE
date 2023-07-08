<?php 
  require_once "classes/connection/Connection.php";
  $conn = new Connection;
  $query = "select * from pacientes";
  print_r($conn->GetData($query));
?>
