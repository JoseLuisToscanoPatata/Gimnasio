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

INSERT INTO actividad (actividad_id, act_nombre, aforo, descripcion) VALUES (NULL, 'Fitness',20,'No se lo que se hace aquí jaja saludos');
INSERT INTO actividad (actividad_id, act_nombre, aforo, descripcion) VALUES (NULL, 'Body Combat',15,'Tampoco sé lo que se hace aquí');
INSERT INTO actividad (actividad_id, act_nombre, aforo, descripcion) VALUES (NULL, 'Yoga',30,'Mi personaje de star wars favorito');
INSERT INTO actividad (actividad_id, act_nombre, aforo, descripcion) VALUES (NULL, 'Pilates',23,'Para estar mamadisimo, mentira, tampoco sé lo que se hace');
INSERT INTO actividad (actividad_id, act_nombre, aforo, descripcion) VALUES (NULL, 'Hora libre',19,'No se me ocurren más actividades');
INSERT INTO actividad (actividad_id, act_nombre, aforo, descripcion) VALUES (NULL, 'Religion',10,'Un 10 gratis pa la nota');

CREATE OR REPLACE TABLE tramo (
   tramo_id int(11) NOT NULL AUTO_INCREMENT,
   dia int(1) NOT NULL,
   hora_inicio time NOT NULL,
   hora_fin time NOT NULL,
   actividad_id int(11) NOT NULL,
   fecha_alta date NOT NULL,
   fecha_baja date DEFAULT NULL,
    FOREIGN KEY(actividad_id) REFERENCES actividad(actividad_id),
    PRIMARY KEY(tramo_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 1, '14:15:00','14:45:00',11,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 2, '12:45:00','13:00:00',7,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 2, '18:00:00','18:30:00',12,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 3, '09:15:00','10:00:00',8,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 3, '17:00:00','17:30:00',10,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 3, '19:30:00','20:15:00',12,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 4, '09:15:00','10:00:00',8,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 4, '12:30:00','13:00:00',9,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 5, '08:30:00','09:15:00',11,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 5, '20:30:00','21:15:00',7,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 6, '13:30:00','14:00:00',10,CURDATE());
 INSERT INTO tramo (tramo_id, dia, hora_inicio, hora_fin, actividad_id, fecha_alta) values (NULL, 6, '21:15:00','22:00:00',7,CURDATE());


CREATE OR REPLACE TABLE usuario (
  usuario_id int(11) NOT NULL AUTO_INCREMENT,
  nif varchar(10),
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
  autentificacion varchar(10) DEFAULT 'nada' COLLATE utf8_unicode_ci,
  idGoogle varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
 FOREIGN KEY (rol_id) REFERENCES rol(rol_id),
  PRIMARY KEY(usuario_id),
  UNIQUE(nif),
  UNIQUE(login),
  UNIQUE(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci;

INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '12345678T', 'Manolo', 'Ortega', 'Cano', NULL, 'Patata',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'patata@hotmail.com', 911223344, 'calle paco', 1, 1);

  INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '98765432P', 'Paca', 'Perez', 'Olga', NULL, 'Zanahoria',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'patata@gmail.com', 811223344, 'calle dora', 2, 1);

INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '44556677P', 'Lola', 'Rodriguez', 'Calvo', NULL, 'Coco',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'lola@hotmail.com', 666555444, 'calle mano', 2, 2);

  INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '12345234K', 'Manoli', 'paca', 'Palo', NULL, 'Silla',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'silla@gmail.com', 914223344, 'calle loli', 1, 1);

  INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '66644412C', 'Javier', 'Caco', 'Martin', NULL, 'Pera',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'murcielago@gmail.es', 915623344, 'calle paco', 2, 1);

INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '44856573P', 'gabri', 'garcia', 'mendez', NULL, 'Manzana',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'lola@gmail.es', 668555444, 'calle lolo', 2, 1);

INSERT INTO usuario (usuario_id, nif, usu_nombre, apellido1, apellido2, imagen, login, password, email,
 telefono, DIRECCION, rol_id, estado) VALUES (NULL, '85156677F', 'marco', 'heidi', 'barranco', NULL, 'Kiwi',
  '556f2f3abcac9f1caa6be0a62ed41ec5d7b43e48', 'rodolfo@gmail.com', 668555879, 'calle abril', 2, 2);

CREATE OR REPLACE TABLE tramo_usuario (
   tramo_usu_id int(11) NOT NULL AUTO_INCREMENT,
   tramo_id int(11) NOT NULL,
   usuario_id int(11) NOT NULL,
   fecha_act date NOT NULL,
   fecha_reserva date NOT NULL,
   PRIMARY KEY(tramo_usu_id),
  FOREIGN KEY(tramo_id) REFERENCES tramo(tramo_id),
  FOREIGN KEY(usuario_id) REFERENCES usuario(usuario_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

 CREATE OR REPLACE TABLE mensaje (
   mensaje_id int(11) NOT NULL AUTO_INCREMENT,
   usu_origen int(11) NOT NULL,
   asunto varchar(30) NOT NULL,
   usu_destino int(11) NOT NULL,
   mensaje varchar(100) NOT NULL,
   PRIMARY KEY(mensaje_id),
   FOREIGN KEY(usu_origen) REFERENCES usuario(usuario_id),
   FOREIGN KEY(usu_destino) REFERENCES usuario(usuario_id)
 )

 INSERT INTO mensaje (mensaje_id, usu_origen, usu_destino, mensaje, asunto) VALUES (NULL, 1, 2, '!Bienvenido a nuestro gimnasio!', 'Bienvenido!');

COMMIT;

