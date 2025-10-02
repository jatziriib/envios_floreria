<?php
require('../../fpdf/fpdf.php'); 

// Conexión
$conn = new mysqli("localhost", "root", "", "floreria", 3308);
if ($conn->connect_error) {
    die("Error conexión: " . $conn->connect_error);
}

$id = intval($_GET['id']);

// Obtener datos del pedido
$sql = "SELECT p.id, p.recibe, p.fecha_envio, p.lugar, p.descripcion, 
               p.metodo_pago, p.estado_pago, v.total_final
        FROM pedido p
        LEFT JOIN vista_pedidos_detalle v ON v.id_pedido = p.id
        WHERE p.id = $id";
$pedido = $conn->query($sql)->fetch_assoc();

// Obtener productos del detalle
$productos = $conn->query("
    SELECT pr.nombre, d.cantidad
    FROM detalle d
    INNER JOIN producto pr ON pr.id = d.id_producto
    WHERE d.id_pedido = $id
");

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();

// Logo
$pdf->Image('../images/logo.png', 10, 8, 30); 
$pdf->SetFont('Helvetica','B',18);
$pdf->Cell(80); 
$pdf->Cell(100,10,'Flores del Guadiana',0,1,'R');
$pdf->Ln(20);

// Título
$pdf->SetFont('Helvetica','B',16);
$pdf->Cell(0,10,"Pedido #".$pedido['id'],0,1,'C');
$pdf->Ln(5);

// Datos del pedido
$pdf->SetFont('Helvetica','',12);
$pdf->Cell(0,8,"Recibe: ".$pedido['recibe'],0,1);
$pdf->Cell(0,8,"Fecha de Envio: ".$pedido['fecha_envio'],0,1);
$pdf->Cell(0,8,"Lugar: ".$pedido['lugar'],0,1);
$pdf->Cell(0,8,"Metodo de pago: ".$pedido['metodo_pago'],0,1);
$pdf->Cell(0,8,"Estado de pago: ".$pedido['estado_pago'],0,1);
$pdf->Cell(0,8,"Descripcion: ".$pedido['descripcion'],0,1);
$pdf->Ln(10);

// Tabla de productos
$pdf->SetFont('Helvetica','B',12);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(120,10,"Producto",1,0,'C',true);
$pdf->Cell(40,10,"Cantidad",1,1,'C',true);

$pdf->SetFont('Helvetica','',12);
while($row = $productos->fetch_assoc()){
    $pdf->Cell(120,10,$row['nombre'],1);
    $pdf->Cell(40,10,$row['cantidad'],1,1,'C');
}

// Total
$pdf->SetFont('Helvetica','B',12);
$pdf->Cell(120,10,"TOTAL",1);
$pdf->Cell(40,10,number_format($pedido['total_final'],2),1,1,'C');

$pdf->Ln(20);
$pdf->SetFont('Helvetica','I',10);
$pdf->Cell(0,10,"Gracias por su compra en Flores del Guadiana",0,1,'C');

$pdf->Output("D","pedido_".$pedido['id'].".pdf");
?>
