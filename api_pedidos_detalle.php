<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents("php://input"), true);

// Conexión
$conn = new mysqli("localhost", "root", "", "floreria", 3308);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}

switch ($method) {

    // 📌 GET - Consultar pedidos con detalle
    case 'GET':
        $id_pedido = $_GET['id_pedido'] ?? null;

        if ($id_pedido) {
            $stmt = $conn->prepare("SELECT * FROM vista_pedidos_detalle WHERE id = ?");
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data);
            $stmt->close();
        } else {
            $result = $conn->query("SELECT * FROM vista_pedidos_detalle");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data);
        }
        break;

    // 📌 POST - Crear nuevo pedido con detalle
    case 'POST':
        if (
            isset($input['id_usuario']) &&
            isset($input['metodo_pago']) &&
            isset($input['estado_pago']) &&
            isset($input['costo_envio']) &&
            isset($input['fecha_envio']) &&
            isset($input['lugar']) &&
            isset($input['descripcion']) &&
            isset($input['recibe']) &&
            isset($input['productos']) && is_array($input['productos'])
        ) {
            // Insertar en pedido
            $stmt = $conn->prepare("
                INSERT INTO pedido (id_usuario, metodo_pago, estado_pago, costo_envio, fecha_envio, lugar, descripcion, recibe)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "issdssss",
                $input['id_usuario'],
                $input['metodo_pago'],
                $input['estado_pago'],
                $input['costo_envio'],
                $input['fecha_envio'],
                $input['lugar'],
                $input['descripcion'],
                $input['recibe']
            );

            if ($stmt->execute()) {
                $id_pedido = $stmt->insert_id;
                $stmt->close();

                // Insertar productos en detalle
                $stmt_det = $conn->prepare("INSERT INTO detalle (id_pedido, id_producto, cantidad) VALUES (?, ?, ?)");
                foreach ($input['productos'] as $prod) {
                    $stmt_det->bind_param("iii", $id_pedido, $prod['id_producto'], $prod['cantidad']);
                    $stmt_det->execute();
                }
                $stmt_det->close();

                echo json_encode(["mensaje" => "Pedido registrado", "id_pedido" => $id_pedido]);
            } else {
                echo json_encode(["error" => "No se pudo registrar el pedido", "detalle" => $stmt->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan campos obligatorios"]);
        }
        break;

    // 📌 DELETE - Eliminar pedido y sus detalles
    case 'DELETE':
        $id_pedido = $input['id_pedido'] ?? ($_GET['id_pedido'] ?? null);

        if (!$id_pedido) {
            http_response_code(400);
            echo json_encode(["error" => "ID de pedido no proporcionado"]);
            break;
        }

        // Primero eliminar detalles
        $stmt = $conn->prepare("DELETE FROM detalle WHERE id_pedido = ?");
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();
        $stmt->close();

        // Luego eliminar pedido
        $stmt = $conn->prepare("DELETE FROM pedido WHERE id = ?");
        $stmt->bind_param("i", $id_pedido);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(["mensaje" => "Pedido eliminado"]);
        } else {
            echo json_encode(["error" => "Pedido no encontrado"]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}

$conn->close();
?>
