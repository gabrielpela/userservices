CREATE TABLE `user` (
    `idUser` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`idUser`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;