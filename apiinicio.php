<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");
$method =$_SERVER['REQUEST_METHOD'];

//conexion
$input =json_decode(file_get_contents('php://input'),true);
$conn = new mysqli("localhost","root","","floreria",3308);

if($conn->connect_error){
    die(json_encode(["error" => "conexion fallida"]));
}

//api para floreria
switch($method){
    case 'GET':
        $accion=$_GET["accion"] ??"";
        switch($accion){
            case 'iniciar':
                $result =$conn->query("SELECT * FROM iniciar");
                $iniciar =$result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($iniciar);
                break;


        }
        break;
    case 'POST':
        $accion = $_GET["accion"] ?? "";
         switch ($accion) {
        case 'buscar_usuario':
            $usuario = $_POST["usuario"] ?? "";
            $contrasena = $_POST["contrasena"] ?? "";

            if ($usuario && $contrasena) {
                $stmt = $conn->prepare("SELECT * FROM iniciar WHERE usuario=?");
                $stmt->bind_param("s", $usuario);
                $stmt->execute();
                $result = $stmt->get_result();
                $datos = $result->fetch_assoc();

                if ($datos && password_verify($contrasena, $datos['contrasena'])) {
                    echo json_encode(["status" => "ok", "usuario" => $datos]);
                } else {
                    echo json_encode(["status" => "error", "mensaje" => "Usuario o contraseña incorrectos"]);
                }
            } else {
                echo json_encode(["status" => "error", "mensaje" => "Parámetros faltantes"]);
            }
            break;
    }
         

}
