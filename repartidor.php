<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

// conexión
$input = json_decode(file_get_contents('php://input'), true);
$conn = new mysqli("localhost", "root", "", "floreria", 3308);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}

// API para los repartidores
switch ($method) {
    case 'GET':
        $accion = $_GET["accion"] ?? "";
        switch ($accion) {
            case 'repartidor':
                $result = $conn->query("SELECT * FROM repartidor");
                $repartidor = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($repartidor);
                break;
            case 'buscar_repartidor':
                $nombre = $_GET["nombre"] ?? "";
                if ($nombre) {
                    $stmt = $conn->prepare("SELECT * FROM repartidor WHERE nombre=?");
                    $stmt->bind_param("s", $nombre);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $datos = $result->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($datos);
                }
                break;
        }
        break;

    case 'POST':
        // Insertar nuevo repartidor
        $id_pedido = $input["id_pedido"] ?? null;
        $nombre = $input["nombre"] ?? null;
        $celular = $input["celular"] ?? null;
        $fecha = $input["fecha"] ?? null;

        if ($id_pedido && $nombre && $celular && $fecha) {
            $stmt = $conn->prepare("INSERT INTO repartidor (id_pedido, nombre, celular, fecha) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_pedido, $nombre, $celular, $fecha);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "id" => $conn->insert_id]);
            } else {
                echo json_encode(["error" => "Error al insertar"]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'PUT':
        // Actualizar repartidor
        $id = $input["id"] ?? null;
        $id_pedido = $input["id_pedido"] ?? null;
        $nombre = $input["nombre"] ?? null;
        $celular = $input["celular"] ?? null;
        $fecha = $input["fecha"] ?? null;

        if ($id && $id_pedido && $nombre && $celular && $fecha) {
            $stmt = $conn->prepare("UPDATE repartidor SET id_pedido=?, nombre=?, celular=?, fecha=? WHERE id=?");
            $stmt->bind_param("isssi", $id_pedido, $nombre, $celular, $fecha, $id);
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["error" => "Error al actualizar"]);
            }
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'DELETE':
        // Eliminar repartidor
        $id = $_GET["id"] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM repartidor WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["error" => "Error al eliminar"]);
            }
        } else {
            echo json_encode(["error" => "ID no especificado"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}
?>
