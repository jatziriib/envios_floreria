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
switch($method){
    case 'GET':
        $accion=$_GET["accion"] ??"";
        switch($accion){
            case 'usuario':
                $result =$conn->query("SELECT * FROM usuario");
                $usuario =$result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($usuario);
                break;
            case 'buscar_usuario':
                $nombre = $_GET ["nombre"] ?? "";
                    if($nombre){
                        $stmt = $conn ->prepare("SELECT * FROM usuario WHERE nombre=?");
                        $stmt -> bind_param("s", $nombre);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        $datos =$result->fetch_all(MYSQLI_ASSOC);
                        echo json_encode($datos);

                    } else {
                        echo json_encode("parametro faltante");
                    }
                    break;

        }
        break;
    case 'POST':
            $stmt =$conn->prepare("insert into usuario (nombre, celular) values (?,?)");
            $stmt->bind_param("ss", $input['nombre'], $input['celular']);
            $mensaje= $stmt->execute()?"usuario registrado":"usuario no registrado";
            echo json_encode(["mensaje" => "$mensaje"]);
            break;
    case 'PUT':
            $putData = json_decode(file_get_contents("php://input"), true);

        // Validar campos obligatorios
        if (
            isset($putData['id']) &&
            isset($putData['nombre']) &&
            isset($putData['celular']) 
        ) {
            $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, celular = ? WHERE id = ?");
            $stmt->bind_param("ssi", 
                $putData['nombre'], 
                $putData['celular'], 
                $putData['id']
            );

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                http_response_code(200);
                echo json_encode(["mensaje" => "Usuario actualizado correctamente"]);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "Usuario no encontrado o sin cambios"]);
            }

            $stmt->close();
        } else { 
            http_response_code(400);
            echo json_encode(["error" => "Faltan campos obligatorios"]);
        }
            break;
        case 'DELETE':
            $deleteData = json_decode(file_get_contents("php://input"), true);
            if (isset($deleteData['id'])) {
                $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
                $stmt->bind_param("i", $deleteData['id']);
                $stmt->execute();
                $stmt->close();
                echo json_encode(["mensaje" => "Producto eliminado correctamente"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID del producto no proporcionado"]);
            }
            break;
}
$conn->close();
