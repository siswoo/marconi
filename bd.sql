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

DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT,
	usuario VARCHAR(250) NOT NULL,
	nombre VARCHAR(250) NOT NULL,
	password VARCHAR(250) NOT NULL,
	rol INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (rol) REFERENCES roles (id) ON DELETE CASCADE ON UPDATE CASCADE
); ALTER TABLE usuarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO usuarios (usuario,nombre,password,rol) VALUES 
('admin','Juan Maldonado','d964173dc44da83eeafa3aebbee9a1a0',1);

DROP TABLE IF EXISTS submodulos;
CREATE TABLE submodulos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	idModulo INT NOT NULL,
	link VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE submodulos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO submodulos (nombre,idModulo,link) VALUES 
('Gestionar',1,'turnos.php');

DROP TABLE IF EXISTS modulos;
CREATE TABLE modulos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE modulos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO modulos (nombre) VALUES 
('Turnos');

DROP TABLE IF EXISTS permisos;
CREATE TABLE permisos (
	id INT AUTO_INCREMENT,
	rolId INT NOT NULL,
	submoduloId INT(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE permisos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO permisos (rolId,submoduloId) VALUES 
(1,1);
