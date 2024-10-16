<?php
// Configura la conexión a la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "producto";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Recibe los datos enviados desde JavaScript
$data = json_decode(file_get_contents("php://input"));

// Valida y procesa los datos del producto
$nombre = $data->nombre;
$descripcion = $data->descripcion;
$precio = $data->precio;

// Realiza la inserción en la tabla del carrito
$sql = "INSERT INTO Carrito (Nombre, Descripcion, Precio) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $nombre, $descripcion, $precio);

$response = array();

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Producto agregado exitosamente al carrito";
} else {
    $response['success'] = false;
    $response['message'] = "Error al agregar el producto al carrito: " . $stmt->error;
}

$stmt->close();

// Devuelve una respuesta JSON a JavaScript
header('Content-Type: application/json');
echo json_encode($response);

// Cierra la conexión a la base de datos
$conn->close();
?>