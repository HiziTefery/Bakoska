    DROP USER  pi_user@localhost;
    DROP DATABASE rpi_db;
    CREATE DATABASE rpi_db;
    USE rpi_db;
    CREATE USER pi_user@localhost IDENTIFIED BY 'arthas4259';
    GRANT ALL ON rpi_db.* TO pi_user@localhost;

    CREATE TABLE IF NOT EXISTS measuredData (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        temperature FLOAT,
        barometric_pressure FLOAT,
        humidity INTEGER,
        timestamp_of_measurement DATETIME NOT NULL
    );

    CREATE TABLE IF NOT EXISTS authentication (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        password VARCHAR(64) NOT NULL,
        username VARCHAR(20) UNIQUE NOT NULL
    );

    CREATE TABLE IF NOT EXISTS configurationData (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        device ENUM('/dev/ttyUSB0','/dev/ttyUSB1','/dev/ttyUSB2','/dev/ttyUSB3','/dev/ttyUSB4') NOT NULL DEFAULT '/dev/ttyUSB0',
        baudrate INTEGER NOT NULL DEFAULT '9600',
        parity ENUM('E','N','O') NOT NULL DEFAULT 'N',
        data_bits ENUM('5','6','7','8')  NOT NULL DEFAULT '8', 
        stop_bits ENUM('1','2') NOT NULL DEFAULT '2', 
        communication_mode ENUM('ascii','rtu') NOT NULL DEFAULT 'rtu'
    );


    delimiter //
    CREATE TRIGGER check_baudrate BEFORE UPDATE ON configurationData
    FOR EACH ROW
    BEGIN
        IF NEW.baudrate < 3000 THEN
        SET NEW.baudrate = 9600;
        ELSEIF NEW.baudrate > 200000 THEN
        SET NEW.baudrate = 9600;
        END IF;
    END;//
    delimiter ;

    delimiter //
    CREATE TRIGGER check_measured_data BEFORE INSERT ON measuredData
    FOR EACH ROW
    BEGIN
        IF (NEW.temperature < -100) OR (NEW.temperature > 100) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot add or update row: value temperature is out of range';
        ELSEIF (NEW.humidity > 100) OR (NEW.humidity < 0)  THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot add or update row: value humidity is out of range';
        ELSEIF (NEW.barometric_pressure < 850.0) OR (NEW.barometric_pressure > 1100.0) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot add or update row: value barometric_pressure is out of range';
        END IF;
    END;//
    delimiter ;

    INSERT INTO configurationData VALUES();
    INSERT INTO measuredData VALUES(1,90,NULL,NULL,NOW());
    INSERT INTO authentication VALUES(1,'$1$31lkuMj4$se07HeI0FfvFtJRSCZwmG.','admin');

