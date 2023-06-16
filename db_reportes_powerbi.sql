CREATE DATABASE db_reportes_powerbi;
USE db_reportes_powerbi;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`roles` (
  `id_rol` INT NOT NULL AUTO_INCREMENT,
  `nombre_rol` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_rol`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`api_token` (
  `id` INT(11) NOT NULL,
  `token_type` VARCHAR(255) NOT NULL,
  `expires_in` VARCHAR(255) NOT NULL,
  `ext_expires_in` VARCHAR(255) NOT NULL,
  `expires_on` VARCHAR(255) NOT NULL,
  `not_before` VARCHAR(255) NOT NULL,
  `resource` VARCHAR(255) NOT NULL,
  `access_token` VARCHAR(2500) NOT NULL,
  `refresh_token` VARCHAR(1000) NOT NULL,
  `id_token` VARCHAR(1000) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `email_usuario` VARCHAR(50) NOT NULL,
  `nombre_usuario` VARCHAR(45) NULL,
  `fk_id_rol` INT NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX cedula_usuario_UNIQUE (`email_usuario`),
  INDEX fk_usuarios_roles_idx (`fk_id_rol`),
  CONSTRAINT `fk_usuarios_roles`
    FOREIGN KEY (`fk_id_rol`)
    REFERENCES `db_reportes_powerbi`.`roles` (`id_rol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`grupos_tableros` (
  `id_gt` VARCHAR(200) NOT NULL,
  `nombre_grupo` VARCHAR(45) NOT NULL,
  `estado_grupo` TINYINT NOT NULL,
  PRIMARY KEY (`id_gt`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`tableros` (
  `id_tablero` VARCHAR(200) NOT NULL,
  `nombre_tablero` VARCHAR(120) NOT NULL,
  `cliente_tablero` VARCHAR(45) NOT NULL,
  `pais_tablero` VARCHAR(45) NOT NULL,
  `linea_tablero` VARCHAR(45) NOT NULL,
  `titulo_tablero` VARCHAR(45) NOT NULL,
  `url_tablero` VARCHAR(200) NOT NULL,
  `estado_tablero` TINYINT NOT NULL,
  `fk_id_gt` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_tablero`),
  INDEX fk_tableros_grupos_tableros1_idx (`fk_id_gt`),
  CONSTRAINT `fk_tableros_grupos_tableros1`
    FOREIGN KEY (`fk_id_gt`)
    REFERENCES `db_reportes_powerbi`.`grupos_tableros` (`id_gt`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`usuarios_has_grupos_tableros` (
  `usuarios_id_usuario` INT NOT NULL,
  `grupos_tableros_id_gt` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `grupos_tableros_id_gt`),
  INDEX fk_usuarios_has_grupos_tableros_grupos_tableros1_idx (`grupos_tableros_id_gt`),
  INDEX fk_usuarios_has_grupos_tableros_usuarios1_idx (`usuarios_id_usuario`),
  CONSTRAINT `fk_usuarios_has_grupos_tableros_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `db_reportes_powerbi`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_grupos_tableros_grupos_tableros1`
    FOREIGN KEY (`grupos_tableros_id_gt`)
    REFERENCES `db_reportes_powerbi`.`grupos_tableros` (`id_gt`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`accesos_grupos` (
  `id_ag` INT NOT NULL AUTO_INCREMENT,
  `fecha_ag` DATE NOT NULL,
  `contador_ag` INT NOT NULL,
  `fk_id_gt` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_ag`),
  INDEX fk_id_gt1_idx (`fk_id_gt`),
  CONSTRAINT `fk_id_gt1`
    FOREIGN KEY (`fk_id_gt`)
    REFERENCES `db_reportes_powerbi`.`grupos_tableros` (`id_gt`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `db_reportes_powerbi`.`accesos_tableros` (
  `id_at` INT NOT NULL AUTO_INCREMENT,
  `fecha_at` DATE NOT NULL,
  `contador_at` INT NOT NULL,
  `fk_id_tablero` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_at`),
  INDEX fk_id_tablero1_idx (`fk_id_tablero`),
  CONSTRAINT `fk_id_tablero1`
    FOREIGN KEY (`fk_id_tablero`)
    REFERENCES `db_reportes_powerbi`.`tableros` (`id_tablero`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

INSERT INTO `db_reportes_powerbi`.`roles` (`id_rol`, `nombre_rol`) VALUES (1, 'Admin');
INSERT INTO `db_reportes_powerbi`.`roles` (`id_rol`, `nombre_rol`) VALUES (2, 'Visualizador');

INSERT INTO `db_reportes_powerbi`.`usuarios` (`id_usuario`, `email_usuario`, `nombre_usuario`, `fk_id_rol`) VALUES (1, 'juan.ruizc@axity.com', 'Sebastian Ruiz', 1);
INSERT INTO `db_reportes_powerbi`.`usuarios` (`id_usuario`, `email_usuario`, `nombre_usuario`, `fk_id_rol`) VALUES (2, 'juan.ruizc2@axity.com', 'Camilo Ruiz', 2);
INSERT INTO `db_reportes_powerbi`.`usuarios` (`id_usuario`, `email_usuario`, `nombre_usuario`, `fk_id_rol`) VALUES (3, 'juan.ruizc3@axity.com', 'Esteban Ruiz', 2);
