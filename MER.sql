-- Actualizacion De MER por problemas
CREATE TABLE `Usuario` (
  `usuario_ID` INT(6) AUTO_INCREMENT,
  `nombre` VARCHAR(50),
  `apellidos` VARCHAR(50),
  `correo` VARCHAR(50),
  `contraseña` VARCHAR(255), 
  `ROL` ENUM("Estudiante", "Profesor", "Empleado"),
  PRIMARY KEY (`usuario_ID`)
);

CREATE TABLE `Compra` (
  `compra_ID` INT(7) AUTO_INCREMENT,
  `usuario_ID` INT(6),
  `fecha_compra` DATETIME,
  `total` DECIMAL(10,2),
  PRIMARY KEY (`compra_ID`),
  FOREIGN KEY (`usuario_ID`) REFERENCES `Usuario`(`usuario_ID`)
);

CREATE TABLE `Producto` (
  `producto_ID` INT(7) AUTO_INCREMENT,
  `nombre_producto` VARCHAR(50),
  `descripcion` VARCHAR(100),
  `categoria` VARCHAR(100),
  `precio` DECIMAL(10,2), 
  `cantidad` INT(3),
  PRIMARY KEY (`producto_ID`)
);

CREATE TABLE `Detalle_Compra` (
  `detalle_compra_ID` INT(7) AUTO_INCREMENT,
  `compra_ID` INT(7),
  `producto_ID` INT(7),
  `cantidad` INT(5),
  `precio_unitario` DECIMAL(10,2),
  PRIMARY KEY (`detalle_compra_ID`),
  FOREIGN KEY (`compra_ID`) REFERENCES `Compra`(`compra_ID`),
  FOREIGN KEY (`producto_ID`) REFERENCES `Producto`(`producto_ID`)
);

CREATE TABLE `Retiro` (
  `retiro_ID` INT(7) AUTO_INCREMENT,
  `compra_ID` INT(7),
  `fecha_retiro` DATETIME,
  `estado_retiro` ENUM("Pendiente", "Listo para Retiro", "Completado"),
  PRIMARY KEY (`retiro_ID`),
  FOREIGN KEY (`compra_ID`) REFERENCES `Compra`(`compra_ID`)
);
