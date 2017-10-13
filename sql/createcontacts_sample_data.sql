CREATE DATABASE IF NOT EXISTS jobsearch;
use jobsearch;

DROP TABLE IF EXISTS contacts;

CREATE TABLE contacts (
	contactname VARCHAR(30),
	contactcomp VARCHAR(30),
	contactjobtype CHAR(1),
	contactemail CHAR(30),
	contactphone VARCHAR(25),
	contactfax VARCHAR(25)
);

INSERT into contacts
	VALUES ("No Contact", "No Company", "1", "", "", "");

INSERT into contacts
	VALUES ("George Smith", "First Company", "1", "gsmith@firstco.com", "(123) 456-7890", "(549) 333-3590");

INSERT into contacts
	VALUES ("Ed Asner", "Second Company", "2", "easner@secondco.com", "(123) 456-7891", "(625) 985-4321");

INSERT into contacts
	VALUES ("Henry Thorough", "Second Company", "1", "hthorough@secondco.org", "(549) 333-3589", "(987) 654-1239");

INSERT into contacts
	VALUES ("Bill Schmidt", "Second Company", "2 ", "bschmidt@secondco.org", "(123) 456-7891", "(625) 985-4321");
