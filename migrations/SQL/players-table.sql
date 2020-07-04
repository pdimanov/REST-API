CREATE TABLE IF NOT EXISTS player (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    birth_date DATE NOT NULL
)
DEFAULT CHARACTER SET utf8
COLLATE `utf8_general_ci`
ENGINE = InnoDB;
