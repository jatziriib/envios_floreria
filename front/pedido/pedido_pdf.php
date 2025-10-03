<?php
require('../../fpdf/fpdf.php'); 

// Conexión
$conn = new mysqli("localhost", "root", "", "floreria", 3308);
if ($conn->connect_error) {
    die("Error conexión: " . $conn->connect_error);
}

$id = intval($_GET['id']);

// --- Obtener datos del pedido incluyendo costo de envío ---
$sql = "SELECT p.id, p.recibe, p.fecha_envio, p.lugar, p.descripcion, 
               p.metodo_pago, p.estado_pago, p.costo_envio, v.total_final
        FROM pedido p
        LEFT JOIN vista_pedidos_detalle v ON v.id_pedido = p.id
        WHERE p.id = $id";
$pedido = $conn->query($sql)->fetch_assoc();

// --- Obtener celular del usuario ---
$stmt = $conn->prepare("SELECT celular FROM usuario WHERE nombre = ?");
$stmt->bind_param("s", $pedido['recibe']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$celular_usuario = $usuario['celular'] ?? 'No registrado';

// --- Obtener productos del detalle incluyendo precio unitario ---
$productos = $conn->query("
    SELECT pr.nombre, pr.precio AS precio_unitario, d.cantidad
    FROM detalle d
    INNER JOIN producto pr ON pr.id = d.id_producto
    WHERE d.id_pedido = $id
");

// --- Crear PDF ---
$pdf = new FPDF();
$pdf->AddPage();

// --- Logo y datos de la empresa ---
$pdf->Image('../images/logo.png', 10, 8, 40); 
$pdf->SetFont('Helvetica','B',12);
$pdf->SetXY(55, 10);
$pdf->Cell(0,6,utf8_decode('Flores del Guadiana'),0,1,'L');
$pdf->SetX(55);
$pdf->SetFont('Helvetica','',11);
$pdf->Cell(0,6,utf8_decode('Ma. Manuela Guereca Campos'),0,1,'L');
$pdf->SetX(55);
$pdf->Cell(0,6,utf8_decode('Prol. Libertad No. 213 Nte. Fracc. La Forestal C.P.34217'),0,1,'L');
$pdf->SetX(55);
$pdf->Cell(0,6,utf8_decode('Of. (618) 129-01-22 Cel. (618) 364-52-38'),0,1,'L');
$pdf->SetX(55);
$pdf->Cell(0,6,utf8_decode('Y (618) 309-13-47 Durango Dgo'),0,1,'L');

$pdf->Ln(10);

// --- Título ---
$pdf->SetFont('Helvetica','B',16);
$pdf->Cell(0,10,utf8_decode("NOTA DE VENTA"),0,1,'C');
$pdf->Ln(5);

// --- Tabla de datos del pedido ---
$pdf->SetFont('Helvetica','B',12);
$pdf->SetFillColor(230,230,230);

$datosPedido = [
    ["Recibe", $pedido['recibe']],
    ["Celular", $celular_usuario],
    ["Fecha de Envío", $pedido['fecha_envio']],
    ["Lugar", $pedido['lugar']],
    ["Método de Pago", $pedido['metodo_pago']],
    ["Estado de Pago", $pedido['estado_pago']],
    ["Descripción", $pedido['descripcion']]
];

foreach($datosPedido as $dato){
    $pdf->Cell(50,8,utf8_decode($dato[0]),1,0,'C',true);
    $pdf->MultiCell(130,8,utf8_decode($dato[1]),1,'L');
}

$pdf->Ln(5);

// --- Tabla de productos ---
$pdf->SetFont('Helvetica','B',12);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(80,10,utf8_decode("Producto"),1,0,'C',true);
$pdf->Cell(30,10,"Cantidad",1,0,'C',true);
$pdf->Cell(30,10,utf8_decode("Precio Unit."),1,0,'C',true);
$pdf->Cell(40,10,utf8_decode("Subtotal"),1,1,'C',true);

$pdf->SetFont('Helvetica','',12);
$subtotal_total = 0;
while($row = $productos->fetch_assoc()){
    $subtotal = $row['cantidad'] * $row['precio_unitario'];
    $subtotal_total += $subtotal;

    $pdf->MultiCell(80,8,utf8_decode($row['nombre']),1);
    $y_current = $pdf->GetY();
    $pdf->SetY($y_current-8);
    $pdf->SetX(90);
    $pdf->Cell(30,8,$row['cantidad'],1,0,'C');
    $pdf->Cell(30,8,number_format($row['precio_unitario'],2),1,0,'C');
    $pdf->Cell(40,8,number_format($subtotal,2),1,1,'C');
}

// --- Costo de envío ---
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(140,10,utf8_decode("Costo de Envío"),1);
$pdf->Cell(40,10,number_format($pedido['costo_envio'],2),1,1,'C');

// --- Total general ---
$total_general = $subtotal_total + $pedido['costo_envio'];
$pdf->Cell(140,10,utf8_decode("TOTAL GENERAL"),1);
$pdf->Cell(40,10,number_format($total_general,2),1,1,'C');

$pdf->Ln(15);
$pdf->SetFont('Helvetica','I',10);
$pdf->Cell(0,10,utf8_decode("Gracias por su compra en Flores del Guadiana"),0,1,'C');

$pdf->Output("D","nota_venta_".$pedido['id'].".pdf");
?>
