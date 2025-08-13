<?php
header("Content-Type: application/json; charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];

// Leer JSON de entrada
$input = json_decode(file_get_contents('php://input'), true);

// Conexión a la BD
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
                echo json_encode($result->fetch_all(MYSQLI_ASSOC));
                break;

            case 'buscar_producto':
                $nombre = $_GET["nombre"] ?? "";
                if ($nombre) {
                    $stmt = $conn->prepare("SELECT * FROM producto WHERE nombre = ?");
                    $stmt->bind_param("s", $nombre);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
                } else {
                    echo json_encode(["error" => "Parámetro faltante"]);
                }
                break;
        }
        break;

    case 'POST':
        if (isset($input['nombre'], $input['precio'], $input['descripcion'])) {
            $stmt = $conn->prepare("INSERT INTO producto (nombre, precio, descripcion) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $input['nombre'], $input['precio'], $input['descripcion']);
            $mensaje = $stmt->execute()
                ? ["mensaje" => "Producto registrado correctamente"]
                : ["error" => "Producto no registrado"];
            echo json_encode($mensaje);
        } else {
            echo json_encode(["error" => "Faltan campos"]);
        }
        break;

    case 'PUT':
        if (isset($input['id'], $input['nombre'], $input['precio'], $input['descripcion'])) {
            $stmt = $conn->prepare("UPDATE producto SET nombre = ?, precio = ?, descripcion = ? WHERE id = ?");
            $stmt->bind_param("sdsi", $input['nombre'], $input['precio'], $input['descripcion'], $input['id']);
            $stmt->execute();
            echo json_encode(
                $stmt->affected_rows > 0
                    ? ["mensaje" => "Producto actualizado correctamente"]
                    : ["error" => "Producto no encontrado o sin cambios"]
            );
        } else {
            echo json_encode(["error" => "Faltan campos"]);
        }
        break;

    case 'DELETE':
        if (isset($input['id'])) {
            $stmt = $conn->prepare("DELETE FROM producto WHERE id = ?");
            $stmt->bind_param("i", $input['id']);
            $stmt->execute();
            echo json_encode(["mensaje" => "Producto eliminado correctamente"]);
        } else {
            echo json_encode(["error" => "ID del producto no proporcionado"]);
        }
        break;
}

$conn->close();
