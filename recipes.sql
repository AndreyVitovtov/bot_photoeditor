-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema admin_shefpovar
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema admin_shefpovar
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `admin_shefpovar` DEFAULT CHARACTER SET utf8 ;
USE `admin_shefpovar` ;

-- -----------------------------------------------------
-- Table `admin_shefpovar`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `chat` VARCHAR(255) NULL,
  `username` VARCHAR(255) NULL,
  `first_name` VARCHAR(255) NULL,
  `last_name` VARCHAR(255) NULL,
  `country` VARCHAR(255) NOT NULL,
  `messenger` VARCHAR(255) NULL,
  `access` INT NULL DEFAULT 0,
  `date` DATE NULL,
  `time` TIME NULL,
  `active` INT NULL,
  `start` INT NULL DEFAULT 0,
  `count_ref` INT NULL DEFAULT 0,
  `count_read` INT NULL DEFAULT 0,
  `access_free` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`referral_system`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`referral_system` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `referrer` INT UNSIGNED NOT NULL,
  `referral` INT UNSIGNED NOT NULL,
  `date` DATE NULL,
  `time` TIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_referral_system_users_idx` (`referrer` ASC),
  INDEX `fk_referral_system_users1_idx` (`referral` ASC),
  CONSTRAINT `fk_referral_system_users`
    FOREIGN KEY (`referrer`)
    REFERENCES `admin_shefpovar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_referral_system_users1`
    FOREIGN KEY (`referral`)
    REFERENCES `admin_shefpovar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`heading`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`heading` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`recipes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`recipes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `heading_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NULL,
  `text` TEXT NULL,
  `img` VARCHAR(255) NULL,
  `active` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_recipes_heading1_idx` (`heading_id` ASC),
  CONSTRAINT `fk_recipes_heading1`
    FOREIGN KEY (`heading_id`)
    REFERENCES `admin_shefpovar`.`heading` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`offers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`offers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT UNSIGNED NOT NULL,
  `recipes_id` INT UNSIGNED NOT NULL,
  `date` DATE NULL,
  `time` TIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_offers_recipes1_idx` (`recipes_id` ASC),
  INDEX `fk_offers_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_offers_recipes1`
    FOREIGN KEY (`recipes_id`)
    REFERENCES `admin_shefpovar`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_offers_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `admin_shefpovar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(255) NULL,
  `password` VARCHAR(255) NULL,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`answers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(255) NULL,
  `answer` TEXT NULL,
  `method` VARCHAR(255) NULL,
  `menu` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`interaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`interaction` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT UNSIGNED NOT NULL,
  `command` VARCHAR(255) NULL,
  `params` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_interaction_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_interaction_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `admin_shefpovar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`settings_buttons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`settings_buttons` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `text` VARCHAR(255) NULL,
  `menu` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`settings_main`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`settings_main` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `prefix` VARCHAR(255) NULL,
  `name` VARCHAR(255) NULL,
  `value` VARCHAR(255) NULL,
  `type` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`settings_pages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`settings_pages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `text` TEXT NULL,
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`contacts_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`contacts_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `admin_shefpovar`.`contacts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_shefpovar`.`contacts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `contacts_type_id` INT NOT NULL,
  `users_id` INT UNSIGNED NOT NULL,
  `text` TEXT NULL,
  `date` DATE NULL,
  `time` TIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_contacts_users1_idx` (`users_id` ASC),
  INDEX `fk_contacts_contacts_type1_idx` (`contacts_type_id` ASC),
  CONSTRAINT `fk_contacts_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `admin_shefpovar`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contacts_contacts_type1`
    FOREIGN KEY (`contacts_type_id`)
    REFERENCES `admin_shefpovar`.`contacts_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `admin_shefpovar`;

DELIMITER $$
USE `admin_shefpovar`$$
CREATE DEFINER = CURRENT_USER TRIGGER `admin_shefpovar`.`referral_system_AFTER_INSERT` AFTER INSERT ON `referral_system` FOR EACH ROW
BEGIN
	UPDATE users
	SET count_ref = (SELECT COUNT(*) FROM referral_system WHERE referrer = NEW.referrer)
    WHERE id = NEW.referrer;
END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
