CREATE DATABASE Uber;

use Uber;

CREATE TABLE Trip (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	startLocation VARCHAR(30) NOT NULL,
	destination VARCHAR(30) NOT NULL,
	vehicleType VARCHAR(10) NOT NULL,
	driverID INT(5),
	tripDate DATE,
	date TIMESTAMP
	
	ALTER TABLE Trip
	ADD noOFpassengers INT(3),
	ADD noOftaxis INT(2);
	);

CREATE TABLE Passenger (
	userID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(50) NOT NULL,
	gender ENUM('male', 'female', 'other') NOT NULL,
	mobile INT(10) UNSIGNED ZEROFILL NOT NULL,
	email VARCHAR(30) NOT NULL
	
);
CREATE TABLE Trip_Passenger (
    trip_passenger_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    trip_id INT(11) UNSIGNED NOT NULL,
    passenger_id INT(11) UNSIGNED NOT NULL,
    FOREIGN KEY (trip_id) REFERENCES Trip(id),
    FOREIGN KEY (passenger_id) REFERENCES Passenger(userID)
);

CREATE INDEX idx_destination ON Trip(destination);
CREATE INDEX idx_tripAdd_time ON Trip(date);

CREATE INDEX idx_name ON Passenger(name);




