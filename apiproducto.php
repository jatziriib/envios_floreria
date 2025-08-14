<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

$input = json_decode(file_get_contents('php://input'), true);

// Conexión
$conn = new mysqli("localhost", "root", "", "floreria", 3308);
if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida"]);
    exit;
}

switch ($method) {
    case 'GET':
        $accion = $_GET["accion"] ?? "";
        switch ($accion) {
            case 'producto':
                $result = $conn->query("SELECT * FROM producto");
                $producto = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($producto);
                break;

            case 'buscar_producto':
                $nombre = $_GET["nombre"] ?? "";
                if ($nombre) {
                    $stmt = $conn->prepare("SELECT * FROM producto WHERE nombre = ?");
                    $stmt->bind_param("s", $nombre);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $datos = $result->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($datos);
                } else {
                    echo json_encode(["error" => "Parámetro faltante"]);
                }
                break;

            default:
                echo json_encode(["error" => "Acción no válida"]);
        }
        break;

    case 'POST':
        if (!isset($input['nombre'], $input['precio'], $input['descripcion'], $input['categoria'])) {
            echo json_encode(["error" => "Faltan campos obligatorios"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO producto (nombre, precio, descripcion, categoria) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $input['nombre'], $input['precio'], $input['descripcion'], $input['categoria']);
        $mensaje = $stmt->execute() ? "Producto registrado" : "Producto no registrado";
        echo json_encode(["mensaje" => $mensaje]);
        break;

    case 'PUT':
        if (!isset($input['id'], $input['nombre'], $input['precio'], $input['descripcion'], $input['categoria'])) {
            echo json_encode(["error" => "Faltan campos obligatorios"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE producto SET nombre = ?, precio = ?, descripcion = ?, categoria = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $input['nombre'], $input['precio'], $input['descripcion'], $input['categoria'], $input['id']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["mensaje" => "Producto actualizado correctamente"]);
        } else {
            echo json_encode(["mensaje" => "Producto no encontrado o sin cambios"]);
        }
        break;

    case 'DELETE':
        if (!isset($input['id'])) {
            echo json_encode(["error" => "ID del producto no proporcionado"]);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM producto WHERE id = ?");
        $stmt->bind_param("i", $input['id']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["mensaje" => "Producto eliminado correctamente"]);
        } else {
            echo json_encode(["mensaje" => "Producto no encontrado"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
}

$conn->close();
