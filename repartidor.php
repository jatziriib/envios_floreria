<?php
header("Content-Type: application/json");
$method =$_SERVER['REQUEST_METHOD'];

//conexion
$input =json_decode(file_get_contents('php://input'),true);
$conn = new mysqli("localhost","root","","floreria",3308);

if($conn->connect_error){
    die(json_encode(["error" => "conexion fallida"]));
}

//api para los repartidores 
switch($method){
    case 'GET':
        $accion=$_GET["accion"] ?? "";
        switch ($accion) {
            case 'repartidor':
                $result =$conn ->query("SELECT * FROM repartidor");
                $repartidor =$result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($repartidor);
                break;
            case 'busacar_repartidor':
                $nombre = $_GET ["nombre"] ?? "";
                if($nombre){
                    $stmt = $conn ->prepare("SELECT * FROM repartidor WHERE nombre=?");
                    $stmt -> bind_param("s", $nombre);
                    $stmt->execute();
                    $result=$stmt->get_result();
                    $datos =$result->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($datos);
                }
                # code...
                break;
        }
        break;
    case 'POST':
        

}