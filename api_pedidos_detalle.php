<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

// Conexión
$conn = new mysqli("localhost", "root", "", "floreria", 3308);
if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

switch ($method) {
    case 'GET':
        $accion = $_GET["accion"] ?? "";
        switch($accion){
           case 'buscar_pedido':
    if (isset($_GET['recibe']) && $_GET['recibe'] !== '') {
        $recibe = strtolower($conn->real_escape_string($_GET['recibe']));
        $sql = "SELECT 
                    id_pedido AS id,
                    recibe,
                    fecha_envio,
                    total_final,
                    estado_pago,
                    productos
                FROM vista_pedidos_detalle 
                WHERE LOWER(recibe) LIKE '%$recibe%'";
    } else {
        $sql = "SELECT 
                    id_pedido AS id,
                    recibe,
                    fecha_envio,
                    total_final,
                    estado_pago,
                    productos
                FROM vista_pedidos_detalle";
    }

    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];

        if (!isset($data[$id])) {
            $data[$id] = [
                "id"          => $row["id"],
                "recibe"      => $row["recibe"],
                "fecha_envio" => $row["fecha_envio"],
                "total_final" => $row["total_final"],
                "estado_pago" => $row["estado_pago"],
                "productos"   => []
            ];
        }

        // Extraer nombre y cantidad del campo productos
        // Ejemplo: "Cubo de rosas (6)"
        $nombre = $row["productos"];
        $cantidad = null;

        if (preg_match('/^(.*)\((\d+)\)$/', $row["productos"], $matches)) {
            $nombre = trim($matches[1]);   // "Cubo de rosas"
            $cantidad = intval($matches[2]); // 6
        }

        $data[$id]["productos"][] = [
            "nombre"   => $nombre,
            "cantidad" => $cantidad
        ];
    }

    echo json_encode(array_values($data));
    break;

     case 'pedidos':
    // Traemos los pedidos con el total desde la vista
    $result = $conn->query("
        SELECT 
            p.id,
            u.nombre AS usuario,
            p.id_usuario,
            p.metodo_pago,
            p.estado_pago,
            p.costo_envio,
            p.fecha_envio,
            p.lugar,
            p.descripcion,
            p.recibe,
            v.total_final
        FROM pedido p
        INNER JOIN usuario u ON u.id = p.id_usuario
        LEFT JOIN vista_pedidos_detalle v ON v.id_pedido = p.id
    ");

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $pedido_id = $row['id'];

        // Obtener productos reales desde detalle
        $productos = [];
        $det = $conn->query("
            SELECT d.id_producto, pr.nombre, d.cantidad
            FROM detalle d
            INNER JOIN producto pr ON pr.id = d.id_producto
            WHERE d.id_pedido = {$pedido_id}
        ");

        while ($pd = $det->fetch_assoc()) {
            $productos[] = $pd; // id_producto, nombre, cantidad
        }

        $row['productos'] = $productos;

        $data[] = $row;
    }

    echo json_encode($data);
    break;




        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        // Insertar en pedido
        $stmt = $conn->prepare("INSERT INTO pedido (id_usuario, metodo_pago, estado_pago, costo_envio, fecha_envio, lugar, descripcion, recibe) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
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
        $stmt->execute();
        $pedido_id = $stmt->insert_id;

        // Insertar en detalle
        foreach ($input['productos'] as $producto) {
            $stmt_det = $conn->prepare("INSERT INTO detalle (id_pedido, id_producto, cantidad) VALUES (?, ?, ?)");
            $stmt_det->bind_param("iii", $pedido_id, $producto['id_producto'], $producto['cantidad']);
            $stmt_det->execute();
        }

        echo json_encode(["message" => "Pedido creado con éxito", "id_pedido" => $pedido_id]);
        break;

    // ----------------- PUT -----------------
    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);

        $stmt = $conn->prepare("UPDATE pedido SET id_usuario=?, metodo_pago=?, estado_pago=?, costo_envio=?, fecha_envio=?, lugar=?, descripcion=?, recibe=? WHERE id=?");
        $stmt->bind_param(
            "issdssssi",
            $input['id_usuario'],
            $input['metodo_pago'],
            $input['estado_pago'],
            $input['costo_envio'],
            $input['fecha_envio'],
            $input['lugar'],
            $input['descripcion'],
            $input['recibe'],
            $input['id_pedido']
        );
        $stmt->execute();

        // Borrar detalle viejo
        $conn->query("DELETE FROM detalle WHERE id_pedido=" . intval($input['id_pedido']));

        // Insertar nuevo detalle
        foreach ($input['productos'] as $producto) {
            $stmt_det = $conn->prepare("INSERT INTO detalle (id_pedido, id_producto, cantidad) VALUES (?, ?, ?)");
            $stmt_det->bind_param("iii", $input['id_pedido'], $producto['id_producto'], $producto['cantidad']);
            $stmt_det->execute();
        }

        echo json_encode(["message" => "Pedido actualizado con éxito"]);
        break;

    // ----------------- DELETE -----------------
    case 'DELETE':
        $id = intval($_GET['id']);
        $conn->query("DELETE FROM detalle WHERE id_pedido=$id");
        $conn->query("DELETE FROM pedido WHERE id=$id");
        echo json_encode(["message" => "Pedido eliminado con éxito"]);
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}

$conn->close();
?>
