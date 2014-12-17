    DROP USER  pi_user@localhost;
    DROP DATABASE rpi_db;
    CREATE DATABASE rpi_db;
    USE rpi_db;
    CREATE USER pi_user@localhost IDENTIFIED BY 'arthas4259';
    GRANT ALL ON rpi_db.* TO pi_user@localhost;

    CREATE TABLE IF NOT EXISTS measuredData (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        temperature INTEGER DEFAULT NULL,
        barometric_pressure INTEGER DEFAULT NULL,
        humidity INTEGER DEFAULT NULL,
        timestamp_of_measurement timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS authentication (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        password VARCHAR(64) NOT NULL,
        username VARCHAR(20) UNIQUE NOT NULL
    );

    CREATE TABLE IF NOT EXISTS configurationData (
        id INTEGER PRIMARY KEY AUTO_INCREMENT,
        device ENUM('/dev/ttyAMA0','/dev/ttyAMA1','/dev/ttyAMA2','/dev/ttyAMA3','/dev/ttyAMA4') NOT NULL DEFAULT '/dev/ttyAMA0',
        baudrate ENUM('1200','2400','4800','9600','19200','38400','115200') NOT NULL DEFAULT '19200',
        parity ENUM('E','N','O') NOT NULL DEFAULT 'N',
        data_bits ENUM('5','6','7','8')  NOT NULL DEFAULT '8', 
        stop_bits ENUM('1','2') NOT NULL DEFAULT '1', 
        communication_mode ENUM('ascii','rtu') NOT NULL DEFAULT 'rtu',
        decimal_slave_address INTEGER NOT NULL DEFAULT 1,
        humidity_rA INTEGER DEFAULT 1,
        temperature_rA INTEGER DEFAULT 2,
        barometric_pressure_rA INTEGER DEFAULT 3,
        period_of_measurement INTEGER NOT NULL DEFAULT 10
    ); 
    
    delimiter //
    CREATE TRIGGER check_configuration_data BEFORE UPDATE ON configurationData
    FOR EACH ROW
    BEGIN
        IF NEW.humidity_rA < 0 OR NEW.humidity_rA > 2000 THEN
        SET NEW.humidity_rA = 1;
        ELSEIF NEW.temperature_rA < 0 OR NEW.temperature_rA > 2000 THEN
        SET NEW.temperature_rA = 2;
        ELSEIF NEW.barometric_pressure_rA < 0 OR NEW.barometric_pressure_rA > 2000 THEN
        SET NEW.barometric_pressure_rA = 3;
        ELSEIF NEW.decimal_slave_address < 0 OR NEW.decimal_slave_address > 247 THEN
        SET NEW.decimal_slave_address = 1;
        ELSEIF NEW.period_of_measurement < 5 OR NEW.period_of_measurement > 10000 THEN
        SET NEW.period_of_measurement = 10;
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
        ELSEIF (NEW.barometric_pressure < 850) OR (NEW.barometric_pressure > 1100) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot add or update row: value barometric_pressure is out of range';
        END IF;
    END;//
    delimiter ;

    INSERT INTO configurationData VALUES();
    INSERT INTO measuredData VALUES(1,90,NULL,NULL,NOW());
    INSERT INTO authentication VALUES(1,'$1$31lkuMj4$se07HeI0FfvFtJRSCZwmG.','admin');

