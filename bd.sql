DROP DATABASE IF EXISTS marconi;
CREATE DATABASE marconi;
USE marconi;

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE roles CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO roles (nombre) VALUES 
('Administrador'),
('Colaborador');

DROP TABLE IF EXISTS horarios;
CREATE TABLE horarios (
	id INT AUTO_INCREMENT,
	entrada TIME NOT NULL,
	salida TIME NOT NULL,
	entradaMaxima TIME NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE horarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO horarios (entrada,salida,entradaMaxima) VALUES 
('09:00:00','17:00:00','09:15:00');

DROP TABLE IF EXISTS cargos;
CREATE TABLE cargos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE cargos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO cargos (id,nombre) VALUES 
(1,'cargo1');

DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT,
	usuario VARCHAR(250) NOT NULL,
	nombre VARCHAR(250) NOT NULL,
	apellido VARCHAR(250) NOT NULL,
	apellido2 VARCHAR(250) NOT NULL,
	cedula VARCHAR(250) NOT NULL,
	fechaNacimiento DATE NOT NULL,
	genero VARCHAR(250) NOT NULL,
	telefono VARCHAR(250) NOT NULL,
	correo VARCHAR(250) NOT NULL,
	provincia VARCHAR(250) NOT NULL,
	canton VARCHAR(250) NOT NULL,
	distrito VARCHAR(250) NOT NULL,
	direccion VARCHAR(250) NOT NULL,
	fechaIngreso DATE NOT NULL,
	fechaRetiro DATE NOT NULL,
	salario INT NOT NULL,
	password VARCHAR(250) NOT NULL,
	cargo INT NOT NULL,
	estado VARCHAR(250) DEFAULT 'Activo',
	rol INT NOT NULL,
	horarios INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (rol) REFERENCES roles (id) ON UPDATE CASCADE,
	FOREIGN KEY (horarios) REFERENCES horarios (id) ON UPDATE CASCADE,
	FOREIGN KEY (cargo) REFERENCES cargos (id)
); ALTER TABLE usuarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO usuarios (usuario,nombre,apellido,password,rol,horarios) VALUES 
('admin','Juan','Maldonado','d964173dc44da83eeafa3aebbee9a1a0',1,1);

DROP TABLE IF EXISTS submodulos;
CREATE TABLE submodulos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	idModulo INT NOT NULL,
	link VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE submodulos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO submodulos (nombre,idModulo,link) VALUES 
('Colaboradores',1,'colaboradores.php'),
('Gestionar',2,'turnosGestionar.php'),
('Gestionar',3,'permisosGestionar.php'),
('Gestionar',4,'vacacionesGestionar.php'),
('Gestionar',5,'incapacidadesGestionar.php'),
('Gestionar',6,'liquidacionesGestionar.php'),
('Gestionar',7,'planillasGestionar.php'),
('Gestionar',8,'reportesGestionar.php');

DROP TABLE IF EXISTS modulos;
CREATE TABLE modulos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE modulos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO modulos (nombre) VALUES 
('Mantenimiento'),
('Turnos'),
('Permisos'),
('Vacaciones'),
('Incapacidades'),
('Liquidaciones'),
('Planillas'),
('Reportes');

DROP TABLE IF EXISTS permisos;
CREATE TABLE permisos (
	id INT AUTO_INCREMENT,
	rolId INT NOT NULL,
	submoduloId INT(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE permisos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO permisos (rolId,submoduloId) VALUES 
(1,1),
(1,2),
(1,3),
(2,3),
(1,4),
(2,4),
(1,5),
(2,5),
(1,6),
(1,7),
(1,8);

DROP TABLE IF EXISTS turnos;
CREATE TABLE turnos (
	id INT AUTO_INCREMENT,
	usuarioId INT NOT NULL,
	tipo VARCHAR(250) NOT NULL,
	fechaInicio DATE NOT NULL,
	horaInicio TIME NOT NULL,
	fechaFin DATE NOT NULL,
	horaFin TIME NOT NULL,
	estatusExtras BOOLEAN DEFAULT 0,
	PRIMARY KEY (id)
); ALTER TABLE turnos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS permisosLaborales;
CREATE TABLE permisosLaborales (
	id INT AUTO_INCREMENT,
	usuarioId INT NOT NULL,
	tipo VARCHAR(250) NOT NULL,
	fechaInicio DATE NOT NULL,
	horaInicio TIME NOT NULL,
	horaFin TIME NOT NULL,
	observacion TEXT NOT NULL,
	estatus BOOLEAN DEFAULT 0,
	PRIMARY KEY (id)
); ALTER TABLE permisosLaborales CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS vacaciones;
CREATE TABLE vacaciones (
	id INT AUTO_INCREMENT,
	usuarioId INT NOT NULL,
	fechaInicio DATE NOT NULL,
	observacion TEXT NOT NULL,
	estatus BOOLEAN DEFAULT 0,
	PRIMARY KEY (id)
); ALTER TABLE vacaciones CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS incapacidades;
CREATE TABLE incapacidades (
	id INT AUTO_INCREMENT,
	usuarioId INT NOT NULL,
	fechaInicio DATE NOT NULL,
	fechaFin DATE NOT NULL,
	observacion TEXT NOT NULL,
	estatus BOOLEAN DEFAULT 0,
	diasTotales BOOLEAN DEFAULT 0,
	PRIMARY KEY (id)
); ALTER TABLE incapacidades CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS diasFeriados;
CREATE TABLE diasFeriados (
	id INT AUTO_INCREMENT,
	descripcion VARCHAR(255) NOT NULL,
	mes VARCHAR(2) NOT NULL,
	dia VARCHAR(2) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE diasFeriados CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO diasFeriados (descripcion,mes,dia) VALUES 
('Reyes','01','08');

DROP TABLE IF EXISTS planillas;
CREATE TABLE planillas (
	id INT AUTO_INCREMENT,
	usuarioId INT NOT NULL,
	fecha DATE NOT NULL,
	pagoDia DOUBLE(11,2) NOT NULL,
	pagoHora DOUBLE(11,2) NOT NULL,
	horasExtras INT NOT NULL,
	diasFeriadosLaborados INT NOT NULL,
	aguinaldos DOUBLE(11,2) NOT NULL,
	ccss DOUBLE(11,2) NOT NULL,
	isr DOUBLE(11,2) NOT NULL,
	subTotal DOUBLE(11,2) NOT NULL,
	total DOUBLE(11,2) NOT NULL,
	estatus BOOLEAN DEFAULT 0,
	PRIMARY KEY (id)
); ALTER TABLE planillas CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS liquidaciones;
CREATE TABLE liquidaciones (
	id INT AUTO_INCREMENT,
	usuarioId INT NOT NULL,
	opcion VARCHAR(255) NOT NULL,
	fechaInicio DATE NOT NULL,
	salario DOUBLE(11,2) NOT NULL,
	vacaciones DOUBLE(11,2) NOT NULL,
	aguinaldos DOUBLE(11,2) NOT NULL,
	preaviso DOUBLE(11,2) NOT NULL,
	cesantias DOUBLE(11,2) NOT NULL,
	total INT NOT NULL,
	estatus BOOLEAN DEFAULT 0,
	PRIMARY KEY (id)
); ALTER TABLE liquidaciones CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
