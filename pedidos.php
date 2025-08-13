<?php
header("Content-Type: application/json");
$method =$_SERVER['REQUEST_METHOD'];

//conexion
$input =json_decode(file_get_contents('php://input'),true);
$conn = new mysqli("localhost","root","","floreria",3308);

if($conn->connect_error){
    die(json_encode(["error" => "conexion fallida"]));
}

//api de pedidos 
switch($method){
    case 'GET':
        $accion=$_GET["accion"] ?? "";
        switch($accion){
            case 'pedidos':
                $result =$conn ->query("SELECT * FROM pedidos");
                $pedidos =$result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($pedidos);
                break;
            case 'buscar_pedido':
                $recibe = $_GET ["recibe"] ?? "";
                if($recibe){
                    $stmt = $conn ->prepare("SELECT * FROM pedidos WHERE recibe=?");
                    $stmt -> bind_param("s", $recibe);
                    $stmt->execute();
                    $result=$stmt->get_result();
                    $datos =$result->fetch_all(MYSQLI_ASSOC);
                    echo json_encode($datos);
                }else{
                    echo json_encode("parametro faltante");
                }
                break;
        }
        break;
    case 'POST':
    
}