<?php
header("Content-Type: application/json");
$method =$_SERVER['REQUEST_METHOD'];

//conexion
$input =json_decode(file_get_contents('php://input'),true);
$conn = new mysqli("localhost","root","","floreria",3308);

if($conn->connect_error){
    die(json_encode(["error" => "conexion fallida"]));
}

//api para floreria


switch ($method) {
    case 'POST':
        $accion = $_GET["accion"] ?? "";

        switch ($accion) {
            case 'registrar_usuario':
                $input = json_decode(file_get_contents('php://input'), true);
                $usuario = $input["usuario"] ?? ($_POST["usuario"] ?? "");
                $contrasena = $input["contrasena"] ?? ($_POST["contrasena"] ?? "");

                if ($usuario && $contrasena) {
                    // Encriptar la contraseña
                    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO iniciar (usuario, contrasena) VALUES (?, ?)");
                    $stmt->bind_param("ss", $usuario, $hash);

                    if ($stmt->execute()) {
                        echo json_encode(["status" => "ok", "mensaje" => "Usuario registrado con éxito"]);
                    } else {
                        echo json_encode(["status" => "error", "mensaje" => "Error al registrar usuario"]);
                    }
                } else {
                    echo json_encode(["status" => "error", "mensaje" => "Parámetros faltantes"]);
                }
                break;

            default:
                echo json_encode(["status" => "error", "mensaje" => "Acción POST no válida"]);
                break;
        }
        break;

    default:
        echo json_encode(["status" => "error", "mensaje" => "Método HTTP no permitido"]);
        break;
}