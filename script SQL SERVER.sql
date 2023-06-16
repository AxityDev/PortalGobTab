CREATE DATABASE db_reportes_powerbi;

USE db_reportes_powerbi;

CREATE TABLE roles (
  id_rol INT NOT NULL IDENTITY(1, 1),
  nombre_rol VARCHAR(45) NOT NULL,
  estado_rol tinyint NOT NULL,
  PRIMARY KEY (id_rol)
);

CREATE TABLE fuentes_datos (
  id_fuente INT NOT NULL IDENTITY(1, 1),
  nombre_fuente VARCHAR(45) NOT NULL,
  estado_fuente tinyint NOT NULL,
  PRIMARY KEY (id_fuente)
);

CREATE TABLE api_token (
  id INT NOT NULL IDENTITY(1, 1),
  token_type VARCHAR(255) NOT NULL,
  expires_in VARCHAR(255) NOT NULL,
  ext_expires_in VARCHAR(255) NOT NULL,
  expires_on VARCHAR(255) NOT NULL,
  not_before VARCHAR(255) NOT NULL,
  resource VARCHAR(255) NOT NULL,
  access_token VARCHAR(2500) NOT NULL,
  refresh_token VARCHAR(1500) NOT NULL,
  id_token VARCHAR(1000) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE usuarios (
  id_usuario INT NOT NULL IDENTITY(1, 1),
  email_usuario VARCHAR(50) NOT NULL,
  nombre_usuario VARCHAR(45) NULL,
  fk_id_rol INT NOT NULL,
  PRIMARY KEY (id_usuario),
  CONSTRAINT fk_usuarios_roles FOREIGN KEY (fk_id_rol) REFERENCES roles (id_rol)
);

CREATE UNIQUE INDEX cedula_usuario_UNIQUE ON usuarios (email_usuario);

CREATE TABLE grupos_tableros (
  id_gt VARCHAR(200) NOT NULL,
  nombre_grupo VARCHAR(45) NOT NULL,
  estado_grupo TINYINT NOT NULL,
  PRIMARY KEY (id_gt)
);

CREATE TABLE tableros (
  id_tablero VARCHAR(200) NOT NULL,
  nombre_tablero VARCHAR(120) NOT NULL,
  cliente_tablero VARCHAR(45) NOT NULL,
  pais_tablero VARCHAR(45) NOT NULL,
  linea_tablero VARCHAR(45) NOT NULL,
  titulo_tablero VARCHAR(45) NOT NULL,
  url_tablero VARCHAR(200) NOT NULL,
  desc_tablero VARCHAR(200) NOT NULL,
  comercial_responsable VARCHAR(60),
  lider_responsable VARCHAR(60),
  nombre_pila_cliente VARCHAR(60),
  fecha_publicación DATE,
  actualizacion_automatica TINYINT,
  fk_id_fuente_datos INT NOT NULL,
  estado_tablero TINYINT NOT NULL,
  fk_id_gt VARCHAR(200) NOT NULL,
  PRIMARY KEY (id_tablero),
  CONSTRAINT fk_tableros_grupos_tableros1 FOREIGN KEY (fk_id_gt) REFERENCES grupos_tableros (id_gt),
  CONSTRAINT fk_id_fuente_datos FOREIGN KEY (fk_id_fuente_datos) REFERENCES fuentes_datos (id_fuente)
);

CREATE TABLE usuarios_has_grupos_tableros (
  usuarios_id_usuario INT NOT NULL,
  grupos_tableros_id_gt VARCHAR(200) NOT NULL,
  PRIMARY KEY (usuarios_id_usuario, grupos_tableros_id_gt),
  CONSTRAINT fk_usuarios_has_grupos_tableros_usuarios1 FOREIGN KEY (usuarios_id_usuario) REFERENCES usuarios (id_usuario),
  CONSTRAINT fk_usuarios_has_grupos_tableros_grupos_tableros1 FOREIGN KEY (grupos_tableros_id_gt) REFERENCES grupos_tableros (id_gt)
);

CREATE TABLE tableros_has_fuentes (
  tableros_id_tablero VARCHAR(200) NOT NULL,
  fuentes_id_fuente INT NOT NULL,
  PRIMARY KEY (tableros_id_tablero, fuentes_id_fuente),
  CONSTRAINT fk_tableros_has_fuentes_tableros1 FOREIGN KEY (tableros_id_tablero) REFERENCES tableros (id_tablero),
  CONSTRAINT fk_tableros_has_fuentes_fuentes1 FOREIGN KEY (fuentes_id_fuente) REFERENCES fuentes_datos (id_fuente)
);

CREATE TABLE accesos_grupos (
  id_ag INT NOT NULL IDENTITY(1, 1),
  fecha_ag DATETIME NOT NULL,
  email_usuario VARCHAR(50) NOT NULL,
  fk_id_gt VARCHAR(200) NOT NULL,
  PRIMARY KEY (id_ag),
  CONSTRAINT fk_id_gt1 FOREIGN KEY (fk_id_gt) REFERENCES grupos_tableros (id_gt)
);

CREATE TABLE accesos_tableros (
  id_at INT NOT NULL IDENTITY(1, 1),
  fecha_at DATETIME NOT NULL,
  email_usuario VARCHAR(50) NOT NULL,
  fk_id_tablero VARCHAR(200) NOT NULL,
  PRIMARY KEY (id_at),
  CONSTRAINT fk_id_tablero1 FOREIGN KEY (fk_id_tablero) REFERENCES tableros (id_tablero)
);

INSERT INTO
  roles (nombre_rol, estado_rol)
VALUES
  ('Admin', 1),
  ('Visualizador', 1);

INSERT INTO
  fuentes_datos (nombre_fuente, estado_fuente)
VALUES
  ('Excel', 1),
  ('CSV', 1),
  ('Cisco Prime', 1),
  ('Firewall Paloalto', 1),
  ('SQL SERVER', 1),
  ('ORACLE', 1),
  ('MySQL', 1);

INSERT INTO
  usuarios (email_usuario, fk_id_rol)
VALUES
  ('juan.ruizc@axity.com', 1),
  ('jorge.burgos@axity.com', 2);

INSERT INTO
  [dbo].[api_token] (
    [token_type],
    [expires_in],
    [ext_expires_in],
    [expires_on],
    [not_before],
    [resource],
    [access_token],
    [refresh_token],
    [id_token]
  )
VALUES
  (
    'Bearer',
    '3599',
    '3599',
    '1604951836',
    '1604947936',
    'https://analysis.windows.net/powerbi/api',
    'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6ImtnMkxZczJUMENUaklmajRydDZKSXluZW4zOCIsImtpZCI6ImtnMkxZczJUMENUaklmajRydDZKSXluZW4zOCJ9.eyJhdWQiOiJodHRwczovL2FuYWx5c2lzLndpbmRvd3MubmV0L3Bvd2VyYmkvYXBpIiwiaXNzIjoiaHR0cHM6Ly9zdHMud2luZG93cy5uZXQvMDBhMDVjZTAtYmQzZC00MjE1LWE1NjktYzYyNjFhMjBhMzllLyIsImlhdCI6MTYwNDk0NzkzNiwibmJmIjoxNjA0OTQ3OTM2LCJleHAiOjE2MDQ5NTE4MzYsImFjY3QiOjAsImFjciI6IjEiLCJhaW8iOiJFMlJnWUZoK2xkdW40L0hmWFE4c3pqOFAvS1NRcGpkQmI5Sk9FY1ZtcnltQ3R4dWp2RU1BIiwiYW1yIjpbInB3ZCJdLCJhcHBpZCI6IjBlNmQ2ZmRjLWE5ODYtNDg0Yy05YmEwLTc2MGRhYzY3MDY3OCIsImFwcGlkYWNyIjoiMSIsImZhbWlseV9uYW1lIjoiQ29sb21iaWEiLCJnaXZlbl9uYW1lIjoiQkkiLCJpcGFkZHIiOiIxODYuODUuMjA1LjQ2IiwibmFtZSI6IkJJIENvbG9tYmlhIiwib2lkIjoiNTI3MWU1MGUtNWQyNy00MmQ3LTg3MDMtMzc5NmViNWQzMzI1IiwicHVpZCI6IjEwMDMyMDAwNTBCOUMwQUQiLCJyaCI6IjAuQUFBQTRGeWdBRDI5RlVLbGFjWW1HaUNqbnR4dmJRNkdxVXhJbTZCMkRheG5CbmdQQUNvLiIsInNjcCI6IkFwcC5SZWFkLkFsbCBDYXBhY2l0eS5SZWFkLkFsbCBDYXBhY2l0eS5SZWFkV3JpdGUuQWxsIENvbnRlbnQuQ3JlYXRlIERhc2hib2FyZC5SZWFkLkFsbCBEYXNoYm9hcmQuUmVhZFdyaXRlLkFsbCBEYXRhLkFsdGVyX0FueSBEYXRhZmxvdy5SZWFkLkFsbCBEYXRhZmxvdy5SZWFkV3JpdGUuQWxsIERhdGFzZXQuUmVhZC5BbGwgRGF0YXNldC5SZWFkV3JpdGUuQWxsIEdhdGV3YXkuUmVhZC5BbGwgR2F0ZXdheS5SZWFkV3JpdGUuQWxsIEdyb3VwLlJlYWQgR3JvdXAuUmVhZC5BbGwgTWV0YWRhdGEuVmlld19BbnkgUmVwb3J0LlJlYWQuQWxsIFJlcG9ydC5SZWFkV3JpdGUuQWxsIFN0b3JhZ2VBY2NvdW50LlJlYWQuQWxsIFN0b3JhZ2VBY2NvdW50LlJlYWRXcml0ZS5BbGwgVXNlclN0YXRlLlJlYWRXcml0ZS5BbGwgV29ya3NwYWNlLlJlYWQuQWxsIFdvcmtzcGFjZS5SZWFkV3JpdGUuQWxsIiwic3ViIjoiR085MjA5RU5KV2dEVjNlVzFIRzV1cjh2X2pubmJ2NFo0SnFuRVJ6c3l5dyIsInRpZCI6IjAwYTA1Y2UwLWJkM2QtNDIxNS1hNTY5LWM2MjYxYTIwYTM5ZSIsInVuaXF1ZV9uYW1lIjoiQkkuQ29sb21iaWFAYXhpdHkuY29tIiwidXBuIjoiQkkuQ29sb21iaWFAYXhpdHkuY29tIiwidXRpIjoiTEx1ZEl1YXotMGlRaEJJYy1sVExBUSIsInZlciI6IjEuMCIsIndpZHMiOlsiYjc5ZmJmNGQtM2VmOS00Njg5LTgxNDMtNzZiMTk0ZTg1NTA5Il19.cv1rDj0AGudASrTuFiI1YyACIPtEhG7bokLpYNpAeDqWEEv_V1E4ZxMpxrqpSl-0rrjFBbQZRT25vz6S4IygiSD6Jw2HBE8UMsySfmdykyOfbptk9g9r3G54g5FiddU10kJDlXKjjd52YYskR1hmELFWxFcZl_LeXd7Tme92-tbCTjKQbZv7GYKyRhRee5J7NZqNync4RwpYsN55vHHEkYzY19xZagmyjRUGiqJFpQ2InS1T4Y-uh7k-pA2HrD3LiFGiUVNiMk1tCM5iQpx5npaawePHnFR6lclTH02YZ2ej4hkpsrkU98AvHLV-jWoHDo_pWq8X7S_6ppkosf7RVQ',
    '0.AAAA4FygAD29FUKlacYmGiCjntxvbQ6GqUxIm6B2DaxnBngPACo.AgABAAAAAAB2UyzwtQEKR7-rWbgdcBZIAQDs_wIA9P-Wn1pAlky7KWbWXVBoOM0KJLvHKxVCHp5qP_7XXKaPf_U-6kgF5Kfu3HjkugRujW8SPjRgQF6odPmPdpiSRkXb9hif3a66h-Mewi5jfNx0GjhWHNm_TrNhqUr2aT7ZM2V0FIo6iC2Uhx5bnUXdSXtx61kxuOt5DOof9EvduZVEOH0T34nZhBLtvFqjbpufcqj9xE8bWItDNyduiCFsBxJlyEfq4atGZZPUP0wtq4870Igu5qoPRh6N1VzsJTodYQRvGWQ0d0xUhpehBEbIUWTP14R1GiFbjDTJgZk_FYQ_8AYSyelO0f3Xvrf37IUK1PUAqiMHqpBqpM_wmOnsMmbojH7xRR7MTEp0demfv0aHkLLGuTOG8yYS7a8aVfmMpU1e41lzipt0mcKwUe6C4bq5bMLWZgmz5ZpNVA-aJ5ZHfUX91mPci0A2_GBzhdntytu06EQna9gkgzds9AFmlv10DdRoCIUb6dat8hxm3pXjB22gHuAYn9Fn-pAjU0YzWDcxbba64E8KDEIL_zLNm7n84AF1NN25Od_1oJdpbiLD6lctL_jLl0ZZAU97Rm7oetsOO6xaQJqtS-RvJcqCFSlfQ1vfGcCvCiQjAzuit7KqylpwBjOGp5J1BW95P22_102V8nNa0H7Mo1G7CaweJCUADjyQrNpcigz_vaCwAZdPbj_OvyScQRVF-7lBMt8DtPx9HAUgsrfSP7Mgm_JQEoU9gcxfJw-NjG4M_Ziovt-KwhR1smc2eoArKsvIKe8-nctVmXfbso0LH7C9CVK0H25eb1Zg2TFmAuNGYdIqCzl7AURMOEVtwhEVO2DA6yRUexWLhHmJK-XRM_jfeQ6uL7-8Eu3-drduERx_IAdK7CBrEsW-ACMfSPjSN2esEZBFUGGNEyM89F',
    'eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0.eyJhdWQiOiIwZTZkNmZkYy1hOTg2LTQ4NGMtOWJhMC03NjBkYWM2NzA2NzgiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8wMGEwNWNlMC1iZDNkLTQyMTUtYTU2OS1jNjI2MWEyMGEzOWUvIiwiaWF0IjoxNjA0OTQ3OTM2LCJuYmYiOjE2MDQ5NDc5MzYsImV4cCI6MTYwNDk1MTgzNiwiYW1yIjpbInB3ZCJdLCJmYW1pbHlfbmFtZSI6IkNvbG9tYmlhIiwiZ2l2ZW5fbmFtZSI6IkJJIiwiaXBhZGRyIjoiMTg2Ljg1LjIwNS40NiIsIm5hbWUiOiJCSSBDb2xvbWJpYSIsIm9pZCI6IjUyNzFlNTBlLTVkMjctNDJkNy04NzAzLTM3OTZlYjVkMzMyNSIsInJoIjoiMC5BQUFBNEZ5Z0FEMjlGVUtsYWNZbUdpQ2pudHh2YlE2R3FVeEltNkIyRGF4bkJuZ1BBQ28uIiwic3ViIjoiNzBoQy00LVZ5YWdiZ20wWXdmZzAtTGsyUlg1QVk5SmlRZmhpVHhESzBHayIsInRpZCI6IjAwYTA1Y2UwLWJkM2QtNDIxNS1hNTY5LWM2MjYxYTIwYTM5ZSIsInVuaXF1ZV9uYW1lIjoiQkkuQ29sb21iaWFAYXhpdHkuY29tIiwidXBuIjoiQkkuQ29sb21iaWFAYXhpdHkuY29tIiwidmVyIjoiMS4wIn0.'
  );