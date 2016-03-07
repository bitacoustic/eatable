# Creates the user table for eatable.
#USE student_f15g04;
USE eatable;

DROP TABLE IF EXISTS Images, Users, Restaurants;

CREATE TABLE Users(
	id INT(6) NOT NULL AUTO_INCREMENT,
	fname VARCHAR(30) NOT NULL,
	lname VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(100) NOT NULL,
	session_id VARCHAR(200), 
	PRIMARY KEY (id)
);

CREATE TABLE Restaurants(
	id INT(6) NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	address_street VARCHAR(50) NOT NULL,
	address_city VARCHAR(50) NOT NULL,
	address_state VARCHAR(2) NOT NULL,
	address_postal_code int(5) NOT NULL,
	phone_number VARCHAR(20), 
	keywords VARCHAR(1000),
	PRIMARY KEY (id)
);

CREATE TABLE Images(
	id INT(6) NOT NULL AUTO_INCREMENT,
	image MEDIUMBLOB NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id) REFERENCES Restaurants(id)
);
