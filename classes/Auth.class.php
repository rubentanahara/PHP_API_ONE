<?php
ini_set('display_errors', 1);
error_reporting(~0);
require_once 'connection/Connection.php';
require_once 'ResponseHandler.class.php';

class Auth
{
    private $responseHandler;
    private $conn;
    public function __construct()
    {
        $this->responseHandler = new ResponseHandler;
        $this->conn = new Connection;
    }
    public function login($json)
    {
        $datos = json_decode($json, true);

        if (!isset($datos['usuario']) || !isset($datos["password"])) {
            // Error con los campos
            return $this->responseHandler->errorIncompleteOrInvalidFormat();
        } else {
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = $this->conn->encriptar($password);
            $usuarioData = $this->obtenerDatosUsuario($usuario);

            if ($usuarioData) {

                // Verificar si la contraseña es igual
                if ($password == $usuarioData[0]['Password']) {
                    if ($usuarioData[0]['Estado'] == "Activo") {
                        // Crear el token
                        $token = $this->insertarToken($usuarioData[0]['UsuarioId']);
                        if ($token) {
                            $result = $this->responseHandler->getResponse();
                            $result["result"] = array(
                                "token" => $token
                            );
                            return $result;
                        } else {
                            // Error al guardar el token
                            return $this->responseHandler->errorInternalServerError("Internal server error");
                        }
                    } else {
                        // El usuario está inactivo
                        return $this->responseHandler->errorInvalidData("Inactive user");
                    }
                } else {
                    // La contraseña no es válida
                    return $this->responseHandler->errorInvalidData("Invalid password");
                }
            } else {
                // No existe el usuario
                return $this->responseHandler->errorInvalidData("$usuario doesn't exists");
            }
        }
    }

    public function obtenerDatosUsuario($correo)
    {
        try {
            $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$correo'";
            $datos = $this->conn->GetData($query);
            if (isset($datos[0]["UsuarioId"])) {
                return $datos;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }


    public function insertarToken($usuarioId)
    {
        $tokenValid = true;
        //bin2hex -> un string hexadecimal
        //openssl_random_pseudo_bytes-> cadena bytes aleatoria
        $token = bin2hex(openssl_random_pseudo_bytes(16, $tokenValid));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES ('$usuarioId', '$token', '$estado', '$date')";
        $verifica = $this->conn->Save($query);

        if ($verifica) {
            return $token;
        } else {
            return 0;
        }
    }
}
