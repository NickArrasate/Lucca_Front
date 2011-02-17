CREATE TABLE IF NOT EXISTS `changelog` (
	`update_number` INT NOT NULL PRIMARY KEY ,
	`db_type` VARCHAR( 100 ) NOT NULL ,
	`skipped` TINYINT NOT NULL ,
	`dt` DATETIME NOT NULL ,
	`dt_undo` DATETIME DEFAULT NULL ,
	`description` VARCHAR( 100 ) NOT NULL
);
