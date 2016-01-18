DROP TABLE companies;

CREATE TABLE companies (
	compname VARCHAR(30),
	compstreet1 VARCHAR(30),
	compstreet2 VARCHAR(30),
	compcitystatezip VARCHAR(40),
    comptype VARCHAR(1),
    compwebsite CHAR(40),
	compemail CHAR(40),
	compphone VARCHAR(25),
	compfax VARCHAR(25),
    comptext TEXT
);

INSERT into companies
	VALUES ("No Company", "", "", "", "1", "", "", "", "", "");

INSERT into companies
	VALUES ("First Company", "123 Any Street", "", "Anaheim, NV  12345", "1", "www.firstco.com", "anyaddy@firstco.com", "(858) 692-8120", "(858) 629-8130", "");

INSERT into companies
	VALUES ("Second Company", "725 Main St", "", "Glendora, CA 91740", "2", "www.secondco.com", "anyaddy@secondco.org", "(345) 246-8024", "(345) 246-8039", "");
	