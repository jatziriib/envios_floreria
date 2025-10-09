<?php
$conn = new mysqli("127.0.0.1", "root", "", "nombre_de_tu_base", 3308);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}
echo "✅ Conexión exitosa a MariaDB en el puerto 3308";
?>
