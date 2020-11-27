CREATE  OR REPLACE TABLE rol (
   rol_id int(11) NOT NULL AUTO_INCREMENT,
   tipo varchar(5) NOT NULL,
   PRIMARY KEY(rol_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

 INSERT INTO rol (rol_id, tipo) VALUES (1, 'admin'),(2, 'socio');

CREATE  OR REPLACE TABLE actividad (
   actividad_id int(11) NOT NULL AUTO_INCREMENT,
   act_nombre varchar(30) NOT NULL,
   descripcion varchar(200) DEFAULT NULL,
   aforo int(2) NOT NULL,
   PRIMARY KEY(actividad_id),
   UNIQUE(act_nombre)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE OR REPLACE TABLE tramo (
   tramo_id int(11) NOT NULL AUTO_INCREMENT,
   dia varchar(9) NOT NULL,
   hora_inicio date NOT NULL,
   hora_fin date NOT NULL,
   actividad_id int(11) NOT NULL,
   fecha_alta date NOT NULL,
   fecha_baja date DEFAULT NULL,
    FOREIGN KEY(actividad_id) REFERENCES actividad(actividad_id),
    PRIMARY KEY(tramo_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE OR REPLACE TABLE usuario (
  usuario_id int(11) NOT NULL AUTO_INCREMENT,
  nif varchar(10) NOT NULL,
  usu_nombre varchar(50) NOT NULL,
   apellido1 varchar(30) NOT NULL,
   apellido2 varchar(30) NOT NULL,
   imagen varchar(150) DEFAULT NULL,
   login varchar(100) NOT NULL,
  password varchar(100) NOT NULL,
  email varchar(50) NOT NULL,
  telefono int(12) NOT NULL,
  direccion varchar(50) NOT NULL,
  rol_id int(11) NOT NULL,
  estado int(1) NOT NULL,
 FOREIGN KEY (rol_id) REFERENCES rol(rol_id),
  PRIMARY KEY(usuario_id),
  UNIQUE(nif),
  UNIQUE(login),
  UNIQUE(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '12345678L', 'Manolo', 'Ortega', 'Cano', NULL, 'Patata',
  'be949382d8cac177e839107051bda29dbc8a11cc', 'patata@manolo.com', 911223344, 'calle paco', 1, 1);

  INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id) VALUES (NULL, '98765432P', 'Paca', 'Perez', 'Olga', NULL, 'Zanahoria',
  'be949382d8cac177e839107051bda29dbc8a11cc', 'patata@paca.com', 111223344, 'calle doro', 2, 1);

INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id) VALUES (NULL, '44556677P', 'Lola', 'Ostia', 'Calvo', NULL, 'Coco',
  'be949382d8cac177e839107051bda29dbc8a11cc', 'lola@paca.com', 666555444, 'calle mano', 2, 2);

CREATE OR REPLACE TABLE tramo_usuario (
   tramo_usu_id int(11) NOT NULL AUTO_INCREMENT,
   tramo_id int(11) NOT NULL,
   usuario_id int(11) NOT NULL,
   fecha_act varchar(9) NOT NULL,
   fecha_reserva date NOT NULL,
   PRIMARY KEY(tramo_usu_id),
  FOREIGN KEY(tramo_id) REFERENCES tramo(tramo_id),
  FOREIGN KEY(usuario_id) REFERENCES usuario(usuario_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

