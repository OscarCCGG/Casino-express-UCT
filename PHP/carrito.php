<?php
session_start();
if (!isset($_SESSION['correo'])) {
    echo "<script>
            alert('Debes iniciar sesión para acceder al carrito.');
            window.location.href='../login.html'; // Redirigir al login
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
    
    <button id="volver" onclick="window.location.href='productos.php'">Volver al Tienda</button>
    <button id="finalizarCompra" onclick="finalizarCompra()">Finalizar Compra</button>

    <script>
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        function mostrarCarrito() {
            const carritoDiv = document.getElementById('carrito');
            carritoDiv.innerHTML = '';

            if (carrito.length === 0) {
                carritoDiv.innerHTML = '<p>No hay productos en el carrito.</p>';
                document.getElementById('total').innerHTML = '';
                return;
            }

            let total = 0;

            carrito.forEach(producto => {
                const productoDiv = document.createElement('div');
                productoDiv.classList.add('producto');
                const subtotalProducto = producto.precio * producto.cantidad;
                total += subtotalProducto;

                productoDiv.innerHTML = `
                    <div class="info">
                        <h2>${producto.nombre}</h2>
                        <p>Precio: $${producto.precio}</p>
                        <p>Cantidad: ${producto.cantidad}</p>
                        <button class="eliminar-button" onclick="eliminarDelCarrito('${producto.nombre}')">Eliminar</button>
                    </div>
                    <img src='../imagenes/${producto.imagen}' alt='${producto.nombre}' />
                `;
                carritoDiv.appendChild(productoDiv);
            });

            // Calcular IVA y total
            const iva = total * 0.19; // Asumiendo un IVA del 19%
            const totalConIVA = total + iva;

            document.getElementById('total').innerHTML = `
                Subtotal: $${total.toFixed(0)}<br>
                IVA (19%): $${iva.toFixed(0)}<br>
                Total: $${totalConIVA.toFixed(0)}
            `;
        }

        function eliminarDelCarrito(nombre) {
            const productoExistente = carrito.find(item => item.nombre === nombre);
            if (productoExistente) {
                if (productoExistente.cantidad > 1) {
                    // Reducir cantidad
                    productoExistente.cantidad--;
                } else {
                    // Eliminar producto si la cantidad es 1
                    const index = carrito.indexOf(productoExistente);
                    carrito.splice(index, 1);
                }
                localStorage.setItem('carrito', JSON.stringify(carrito));
                mostrarCarrito(); // Actualizar vista
            }
        }

        function finalizarCompra() {
            alert('Compra finalizada. ¡Gracias por su compra!');
            localStorage.removeItem('carrito'); // Limpiar carrito después de la compra
            mostrarCarrito(); // Actualizar vista
        }

        mostrarCarrito();
    </script>
</body>
</html>