CREATE DATABASE omnimercado;

CREATE USER 'adminomnimercado'@'localhost' IDENTIFIED BY 'Teori@sistemas2';

GRANT ALL PRIVILEGES ON omnimercado.* TO 'adminomnimercado'@'localhost' WITH GRANT OPTION;

FLUSH PRIVILEGES;

USE omnimercado;

CREATE TABLE restriccion(
    id_restriccion INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(35) NOT NULL UNIQUE,
    cantidad VARCHAR(35) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE rol(
    id_rol INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(35) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE genero(
    id_genero INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE administrativo(
    id_administrativo INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasenia BLOB NOT NULL,
    rol INT NOT NULL,
    url_imagen TEXT NOT NULL,
    activo TINYINT(1) NOT NULL,
    genero INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(rol) REFERENCES rol(id_rol),
    FOREIGN KEY(genero) REFERENCES genero(id_genero)
);

CREATE TABLE usuario(
    id_usuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    contrasenia BLOB NOT NULL,
    moneda_local_gastada DECIMAL(8,2) NOT NULL,
    moneda_local_ganada DECIMAL(8,2) NOT NULL,
    cantidad_moneda_virtual DECIMAL(8,2) NOT NULL,
    credito DECIMAL(8,2) NOT NULL,
    moneda_virtual_ganada DECIMAL(8,2) NOT NULL,
    moneda_virtual_gastada DECIMAL(8,2) NOT NULL,
    promedio_valoracion DECIMAL(3,1)NOT NULL,
    activo_publicar TINYINT(1) NOT NULL,
    activo_plataforma TINYINT(1) NOT NULL,
    genero INT NOT NULL,
    informacion_visible_para_todos TINYINT(1) NOT NULL,
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(genero) REFERENCES genero(id_genero)

);


CREATE TABLE estado_producto(
    id_estado_producto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE tipo_condicion(
    id_tipo_condicion INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE producto(
    id_producto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    precio_moneda_virtual DECIMAL(8,2) NOT NULL,
    descripcion TEXT NOT NULL,
    id_estado_producto INT NOT NULL,
    fecha_publicacion DATE NOT NULL,
    tipo_condicion INT NOT NULL,   
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    id_publicador INT NOT NULL,
    FOREIGN KEY (id_publicador) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_estado_producto) REFERENCES estado_producto(id_estado_producto),
    FOREIGN KEY (tipo_condicion) REFERENCES tipo_condicion(id_tipo_condicion)
);

CREATE TABLE producto_imagen(
    id_url_imagen INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(id_producto) REFERENCES producto(id_producto)
);

CREATE TABLE venta(
    id_venta INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    id_comprador INT NOT NULL,
    fecha_venta DATE NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto),
    FOREIGN KEY (id_comprador) REFERENCES usuario(id_usuario)
);

CREATE TABLE tipo_categoria_producto(
    id_tipo_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(35) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE producto_categoria(
    id_producto_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    id_tipo_categoria INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto),
    FOREIGN KEY (id_tipo_categoria) REFERENCES tipo_categoria_producto(id_tipo_categoria)
);

CREATE TABLE categoria_reporte(
    id_categoria_reporte INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(35) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE reporte_producto(
    id_reporte_producto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_categoria_reporte INT NOT NULL,
    id_producto INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_categoria_reporte) REFERENCES categoria_reporte(id_categoria_reporte),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

CREATE TABLE estado_voluntariado(
    id_estado_voluntariado INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);


CREATE TABLE voluntariado(
    id_voluntariado INT NOT NULL AUTO_INCREMENT,
    codigo_pago VARCHAR(10) NOT NULL,
    titulo VARCHAR(45) NOT NULL,
    retribucion_moneda_virtual DECIMAL(8,2),
    descripcion TEXT NOT NULL,
    lugar VARCHAR(35) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    maximo_voluntariados INT NOT NULL,
    minimo_edad INT NOT NULL,
    maximo_edad INT NOT NULL,
    id_estado INT NOT NULL,
    id_publicador INT NOT NULL,
    descripcion_retribucion TEXT NOT NULL,
    fecha_publicacion DATE NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    PRIMARY KEY(id_voluntariado,codigo_pago),
    FOREIGN KEY (id_publicador) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_estado) REFERENCES estado_voluntariado(id_estado_voluntariado)
);

CREATE TABLE voluntariado_imagen(
    id_url_imagen INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_voluntariado INT NOT NULL,
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(id_voluntariado) REFERENCES voluntariado(id_voluntariado)
);


CREATE TABLE registro_voluntariado(
    id_registro_voluntariado INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_voluntariado INT NOT NULL,
    id_colaborador INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    voluntario_asistio TINYINT(1) NOT NULL,
    FOREIGN KEY (id_voluntariado) REFERENCES voluntariado(id_voluntariado),
    FOREIGN KEY (id_colaborador) REFERENCES usuario(id_usuario)
);

CREATE TABLE tipo_categoria_voluntariado(
    id_tipo_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(35) NOT NULL, 
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE voluntariado_categoria(
    id_voluntariado_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_voluntariado INT NOT NULL,
    id_tipo_categoria INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_voluntariado) REFERENCES voluntariado(id_voluntariado),
    FOREIGN KEY (id_tipo_categoria) REFERENCES tipo_categoria_voluntariado(id_tipo_categoria)
);

CREATE TABLE reporte_voluntariado(
    id_reporte_voluntariado INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_categoria_reporte INT NOT NULL,
    id_voluntariado INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_categoria_reporte) REFERENCES categoria_reporte(id_categoria_reporte),
    FOREIGN KEY (id_voluntariado) REFERENCES voluntariado(id_voluntariado)
);

CREATE TABLE voluntariado_especial(
    id_voluntariado INT NOT NULL AUTO_INCREMENT,
    codigo_pago VARCHAR(10) NOT NULL,
    titulo VARCHAR(45) NOT NULL,
    retribucion_moneda_virtual DECIMAL(8,2),
    descripcion TEXT NOT NULL,
    lugar VARCHAR(35) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    maximo_voluntariados INT NOT NULL,
    minimo_edad INT NOT NULL,
    maximo_edad INT NOT NULL,
    id_estado INT NOT NULL,
    id_publicador INT NOT NULL,
    fecha_publicacion DATE NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    PRIMARY KEY(id_voluntariado,codigo_pago),
    FOREIGN KEY (id_publicador) REFERENCES administrativo(id_administrativo),
    FOREIGN KEY (id_estado) REFERENCES estado_voluntariado(id_estado_voluntariado)
);

CREATE TABLE voluntariado_especial_imagen(
    id_url_imagen INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_voluntariado_especial INT NOT NULL,
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(id_voluntariado_especial) REFERENCES voluntariado_especial(id_voluntariado)
);

CREATE TABLE registro_voluntariado_especial(
    id_registro_voluntariado_especial INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_voluntariado_especial INT NOT NULL,
    id_colaborador INT NOT NULL,
    voluntario_asistio TINYINT(1),
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_voluntariado_especial) REFERENCES voluntariado_especial(id_voluntariado),
    FOREIGN KEY (id_colaborador) REFERENCES usuario(id_usuario)
);

CREATE TABLE voluntariado_especial_categoria(
    id_voluntariado_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_voluntariado_especial INT NOT NULL,
    id_tipo_categoria INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_voluntariado_especial) REFERENCES voluntariado_especial(id_voluntariado),
    FOREIGN KEY (id_tipo_categoria) REFERENCES tipo_categoria_voluntariado(id_tipo_categoria)
);

CREATE TABLE reporte_voluntariado_especial(
    id_reporte_voluntariado INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_categoria_reporte INT NOT NULL,
    id_voluntariado_especial INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_categoria_reporte) REFERENCES categoria_reporte(id_categoria_reporte),
    FOREIGN KEY (id_voluntariado_especial) REFERENCES voluntariado_especial(id_voluntariado)
);

CREATE TABLE chat(
    id_chat INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_comprador INT NOT NULL,
    id_vendedor INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_comprador) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_vendedor) REFERENCES usuario(id_usuario)    
);

CREATE TABLE publicacion_compra(
    id_publicacion_compra INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(45) NOT NULL,
    descripcion TEXT NOT NULL,
    precio_monena_virtual DECIMAL(8,2) NOT NULL,
    id_publicador INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_publicador) REFERENCES usuario(id_usuario)
);

CREATE TABLE publicacion_categoria(
    id_publicacion_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_publicacion_compra INT NOT NULL,
    id_tipo_categoria INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_publicacion_compra) REFERENCES publicacion_compra(id_publicacion_compra),
    FOREIGN KEY (id_tipo_categoria) REFERENCES tipo_categoria_producto(id_tipo_categoria)
);

CREATE TABLE producto_trueque(
    id_producto_trueque INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(45) NOT NULL,
    equivalente_moneda_local DECIMAL(8,2) NOT NULL,
    equivalente_moneda_virtual DECIMAL(8,2) NOT NULL,
    descripcion_producto TEXT NOT NULL,
    id_estado INT NOT NULL,
    fecha_publicacion DATE NOT NULL,
    id_condicion INT NOT NULL,
    descripcion_solicitud TEXT NOT NULL,
    id_publicador INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_estado) REFERENCES estado_producto(id_estado_producto),
    FOREIGN KEY (id_condicion) REFERENCES tipo_condicion(id_tipo_condicion),
    FOREIGN KEY (id_publicador) REFERENCES usuario(id_usuario)
);

CREATE TABLE reporte_producto_trueque(
    id_reporte_producto_trueque INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_categoria_reporte INT NOT NULL,
    id_producto_trueque INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_categoria_reporte) REFERENCES categoria_reporte(id_categoria_reporte),
    FOREIGN KEY (id_producto_trueque) REFERENCES producto_trueque(id_producto_trueque)
);

CREATE TABLE producto_trueque_categoria(
    id_producto_trueque_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_producto_trueque INT NOT NULL,
    id_tipo_categoria INT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_producto_trueque) REFERENCES producto_trueque(id_producto_trueque),
    FOREIGN KEY (id_tipo_categoria) REFERENCES tipo_categoria_producto(id_tipo_categoria)
);

CREATE TABLE producto_trueque_imagen(
    id_url_imagen INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_producto_trueque INT NOT NULL,
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(id_producto_trueque) REFERENCES producto_trueque(id_producto_trueque)
);

CREATE TABLE trueque(
    id_trueque INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_producto_trueque INT NOT NULL,
    id_comprador INT NOT NULL,
    fecha_trueque DATE NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (id_producto_trueque) REFERENCES producto_trueque(id_producto_trueque),
    FOREIGN KEY (id_comprador) REFERENCES usuario(id_usuario)
);

INSERT INTO restriccion(tipo, cantidad,created_at,updated_at) VALUES 
('Aprobaciones minimas',5,'2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Credito maximo','30','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Divisa','0.5','2024-03-08 07:15:30','2024-03-08 07:15:30');


INSERT INTO rol(nombre,created_at,updated_at) VALUES ('Administrador General','2024-03-08 07:15:30','2024-03-08 07:15:30');

INSERT INTO tipo_condicion(nombre,created_at,updated_at) VALUES 
('Nuevo','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Usado','2024-03-08 07:15:30','2024-03-08 07:15:30');

INSERT INTO estado_producto(nombre,created_at,updated_at) values
('Pendiente','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Disponible','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Vendido','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Rechazado','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Eliminado','2024-03-08 07:15:30','2024-03-08 07:15:30');

INSERT INTO genero (nombre) VALUES
('Masculino'),
('Femenino');

INSERT INTO usuario (nombre,correo,fecha_nacimiento,contrasenia,moneda_local_gastada,moneda_local_ganada,cantidad_moneda_virtual,moneda_virtual_ganada,moneda_virtual_gastada,promedio_valoracion,activo_publicar,activo_plataforma,url_imagen,created_at,updated_at,genero,informacion_visible_para_todos,credito) VALUES
	 ('Pedro','c1@correo.com','2000-01-01',0x24327924313024393367376F4268696C6D716561593373396F4A4A496537374A6541656631503369575935724472754844704B4D5479636C4D343632,0.00,0.00,5.00,0.00,0.00,0.0,0,1,'usuario.png','2024-03-08 07:15:30','2024-03-08 07:15:30',1,0,0);

INSERT INTO administrativo(nombre,correo,rol,url_imagen,activo,created_at,updated_at,contrasenia,genero) values 
('Ricardo','c2@correo.com',1,'admin.png',1,'2024-03-08 07:15:30','2024-03-08 07:15:30',0x24327924313024393367376F4268696C6D716561593373396F4A4A496537374A6541656631503369575935724472754844704B4D5479636C4D343632,1);


INSERT INTO tipo_categoria_producto(nombre,created_at,updated_at) values
('Tecnologia','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Computadoras','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Salud','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Belleza','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Vehiculos','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Musica','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Antigüedades','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Hogar','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Herramienta','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Jardineria','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Videojuegos','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Libros','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Peliculas','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Educacion','2024-03-08 07:15:30','2024-03-08 07:15:30');


INSERT INTO estado_voluntariado(nombre,created_at,updated_at) VALUES
('Pendiente','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Disponible','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Realizado','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Rechazado','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Eliminado','2024-03-08 07:15:30','2024-03-08 07:15:30');

INSERT INTO tipo_categoria_voluntariado(nombre,created_at,updated_at) values
('Salud','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Musica','2024-03-08 07:15:30','2024-03-08 07:15:30'),
('Educacion','2024-03-08 07:15:30','2024-03-08 07:15:30');