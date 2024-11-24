<?php
session_start();
if (!isset($_SESSION['correo'])) {
    echo "<script>
            alert('Debes iniciar sesión para acceder al carrito.');
            window.location.href='../login.html';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/carrote.css"> 
    <title>Carrito de Compras</title>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <div id="carrito"></div>
    <div class="total" id="total"></div>
    
    <button id="volver" onclick="window.location.href='productos.php'">Volver a la Tienda</button>
    <button id="finalizarCompra" onclick="finalizarCompra()">Finalizar Compra</button>

    <script>
        // Función para obtener el carrito del usuario
        function obtenerCarrito() {
            fetch('obtener_carro.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarCarrito(data.carrito);
                    } else {
                        mostrarCarrito([]); // Si no hay productos, mostramos el carrito vacío
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de comunicación con el servidor.');
                });
        }

        // Función para mostrar los productos del carrito
        function mostrarCarrito(carrito) {
            const carritoDiv = document.getElementById('carrito');
            carritoDiv.innerHTML = '';
            let total = 0;

            if (carrito.length === 0) {
                carritoDiv.innerHTML = '<p>No hay productos en el carrito.</p>';
                document.getElementById('total').innerHTML = '';
                return;
            }

            carrito.forEach(producto => {
                const subtotal = producto.precio * producto.cantidad;
                total += subtotal;

                const nombre_imagen = producto.nombre_producto.toLowerCase().replace(/ /g, '_') + '.jpg'; // Generamos el nombre de la imagen basado en el nombre del producto.

                carritoDiv.innerHTML += `
                    <div class="producto">
                        <img src="../imagenes/${nombre_imagen}" alt="${producto.nombre_producto}" width="100" />
                        <h2>${producto.nombre_producto}</h2>
                        <p>Precio: $${producto.precio}</p>
                        <p>Cantidad: ${producto.cantidad}</p>
                        <button onclick="eliminarDelCarrito(${producto.producto_ID})">Eliminar</button>
                    </div>
                `;
            });

            const iva = total * 0.19; // IVA del 19%
            const totalConIVA = total + iva;

            document.getElementById('total').innerHTML = `
                Subtotal: $${total.toFixed(0)}<br>
                IVA (19%): $${iva.toFixed(0)}<br>
                Total: $${totalConIVA.toFixed(0)}
            `;
        }

        // Función para eliminar un producto del carrito
        function eliminarDelCarrito(productoID) {
            fetch('eliminar_carro.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ producto_ID: productoID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    obtenerCarrito(); // Recargar el carrito después de eliminar
                } else {
                    alert('Error al eliminar el producto del carrito.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de comunicación con el servidor.');
            });
        }

        // Función para finalizar la compra
        function finalizarCompra() {
            // Verifica si el carrito está vacío
            const carrito = document.getElementById('carrito').children.length;
            if (carrito === 0) {
                alert('No puedes finalizar la compra porque el carrito está vacío.');
                return; // Detenemos la ejecución si el carrito está vacío
            }

            fetch('finalizar_compra.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Compra realizada con éxito.');
                        window.location.href = 'productos.php';
                    } else {
                        alert('Error al finalizar la compra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de comunicación con el servidor.');
                });
        }

        // Llamada inicial para cargar los productos en el carrito
        obtenerCarrito();
    </script>
</body>
</html>
