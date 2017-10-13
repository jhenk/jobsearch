CREATE DATABASE IF NOT EXISTS jobsearch;
use jobsearch;

DROP TABLE IF EXISTS companies;

CREATE TABLE companies (
	compname VARCHAR(30),
	compstreet1 VARCHAR(50),
	compstreet2 VARCHAR(50),
	compcitystatezip VARCHAR(50),
    comptype VARCHAR(1),
    compwebsite CHAR(50),
	compemail CHAR(50),
	compphone VARCHAR(25),
	compfax VARCHAR(25),
    compactive VARCHAR(1),
    comptext TEXT
);

INSERT INTO companies SELECT compname,compstreet1,compstreet2,compcitystatezip,comptype,compwebsite,compemail,compphone,compfax, compactive, comptext FROM companies;
