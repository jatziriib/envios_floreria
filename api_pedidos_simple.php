<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

// conexión
$input = json_decode(file_get_contents('php://input'), true);
$conn = new mysqli("localhost", "root", "", "floreria", 3308);
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}

$accion = $_GET["accion"] ?? "";

switch ($method) {
    case "GET":
        switch ($accion) {
            case "por_nombre":
                if (isset($_GET['recibe']) && $_GET['recibe'] !== '') {
                    $recibe = strtolower($conn->real_escape_string($_GET['recibe']));
                    $sql = "SELECT * 
                            FROM vista_pedidos_simple 
                            WHERE LOWER(recibe) LIKE '%$recibe%'";
                } else {
                    echo json_encode(["error" => "Falta parámetro recibe"]);
                    exit;
                }
                break;

            case "por_fecha":
                if (isset($_GET['fecha']) && $_GET['fecha'] !== '') {
                    $fecha = $conn->real_escape_string($_GET['fecha']);
                    $sql = "SELECT * 
                            FROM vista_pedidos_simple 
                            WHERE DATE(fecha) = '$fecha'";
                } else {
                    echo json_encode(["error" => "Falta parámetro fecha (YYYY-MM-DD)"]);
                    exit;
                }
                break;

            case "todos":
                $sql = "SELECT * FROM vista_pedidos_simple";
                break;

            default:
                echo json_encode(["error" => "Acción inválida"]);
                exit;
        }

        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            // 🔹 Normalizar fecha a solo YYYY-MM-DD
            if (isset($row['fecha'])) {
                $row['fecha'] = date("Y-m-d", strtotime($row['fecha']));
            }
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
}
