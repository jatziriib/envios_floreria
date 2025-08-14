<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

// Conexión
$input = json_decode(file_get_contents('php://input'), true);
$conn = new mysqli("localhost", "root", "", "floreria", 3308);

if ($conn->connect_error) {
    die(json_encode(["error" => "conexion fallida"]));
}

switch ($method) {
    case 'GET':
        $accion = $_GET["accion"] ?? "";
        switch ($accion) {
            case 'pedidos':
                $result = $conn->query("SELECT * FROM pedido");
                $pedidos = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($pedidos);
                break;

            case 'buscar_pedido':
                $recibe = $_GET["recibe"] ?? "";
                if ($recibe) {
                    $stmt = $conn->prepare("SELECT * FROM pedido WHERE recibe=?");
                    $stmt->bind_param("s", $recibe);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $datos = $result->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($datos);
                } else {
                    echo json_encode(["error" => "Parámetro faltante"]);
                }
                break;
        }
        break;


    case 'POST':
        if (
            isset($input['id_usuario']) &&
            isset($input['metodo_pago']) &&
            isset($input['estado_pago']) &&
            isset($input['costo_envio']) &&
            isset($input['total']) &&
            isset($input['fecha_envio']) &&
            isset($input['lugar']) &&
            isset($input['descripcion']) &&
            isset($input['recibe'])
        ) {
            $stmt = $conn->prepare(
                "INSERT INTO pedido 
                (id_usuario, metodo_pago, estado_pago, costo_envio, total, fecha_envio, lugar, descripcion, recibe) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "issddssss",
                $input['id_usuario'],
                $input['metodo_pago'],
                $input['estado_pago'],
                $input['costo_envio'],
                $input['total'],
                $input['fecha_envio'],
                $input['lugar'],
                $input['descripcion'],
                $input['recibe']
            );

            if ($stmt->execute()) {
                echo json_encode(["mensaje" => "Pedido registrado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al registrar el pedido"]);
            }

            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan campos obligatorios"]);
        }
        break;


    case 'PUT':
        $putData = json_decode(file_get_contents("php://input"), true);

        if (
            isset($putData['id']) &&
            isset($putData['id_usuario']) &&
            isset($putData['metodo_pago']) &&
            isset($putData['estado_pago']) &&
            isset($putData['costo_envio']) &&
            isset($putData['total']) &&
            isset($putData['fecha_envio']) &&
            isset($putData['lugar']) &&
            isset($putData['descripcion']) &&
            isset($putData['recibe'])
        ) {
            $stmt = $conn->prepare(
                "UPDATE pedido
                SET id_usuario=?, metodo_pago=?, estado_pago=?, costo_envio=?, total=?, fecha_envio=?, lugar=?, descripcion=?, recibe=? 
                WHERE id=?"
            );

            $stmt->bind_param(
                "issddssssi",
                $putData['id_usuario'],
                $putData['metodo_pago'],
                $putData['estado_pago'],
                $putData['costo_envio'],
                $putData['total'],
                $putData['fecha_envio'],
                $putData['lugar'],
                $putData['descripcion'],
                $putData['recibe'],
                $putData['id']
            );

            if ($stmt->execute()) {
                echo json_encode(["mensaje" => "Pedido actualizado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al actualizar el pedido"]);
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
            $stmt = $conn->prepare("DELETE FROM pedido WHERE id = ?");
            $stmt->bind_param("i", $deleteData['id']);

            if ($stmt->execute()) {
                echo json_encode(["mensaje" => "Pedido eliminado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Error al eliminar el pedido"]);
            }

            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID del pedido no proporcionado"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método HTTP no permitido"]);
        break;
}

$conn->close();
