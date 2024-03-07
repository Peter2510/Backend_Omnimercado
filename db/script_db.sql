CREATE DATABASE omnimercado;

CREATE USER 'adminomnimercado'@'localhost' IDENTIFIED BY 'Teori@sistemas2';

GRANT ALL PRIVILEGES ON omnimercado.* TO 'adminomnimercado'@'localhost' WITH GRANT OPTION;

FLUSH PRIVILEGES;

USE omnimercado;

CREATE TABLE rol(
    id_rol INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

CREATE TABLE administrativo(
    id_administrativo INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasenia BLOB NOT NULL,
    rol INT NOT NULL,
    url_imagen TEXT NOT NULL,
    activo TINYINT(1) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY(rol) REFERENCES rol(id_rol)
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
    moneda_virtual_ganada DECIMAL(8,2) NOT NULL,
    moneda_virtual_gastada DECIMAL(8,2) NOT NULL,
    cantidad_publicaciones_productos INT NOT NULL,
    cantidad_publicaciones_voluntariados INT NOT NULL,
    promedio_valoracion DECIMAL(3,1)NOT NULL,
    activo TINYINT(1) NOT NULL,
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
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
    precio_moneda_local DECIMAL(8,2) NOT NULL,
    precio_moneda_virtual DECIMAL(8,2) NOT NULL,
    descripcion TEXT NOT NULL,
    id_estado_producto INT NOT NULL,
    fecha_publicacion DATE NOT NULL,
    tipo_condicion INT NOT NULL,   
    url_imagen TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
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
    nombre VARCHAR(35) NOT NULL,
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


INSERT INTO rol(nombre) VALUES ('admin');

INSERT INTO tipo_condicion(nombre) VALUES 
('Nuevo'),
('Usado');

INSERT INTO estado_producto(nombre) values
('Disponible'),
('Vendido');


