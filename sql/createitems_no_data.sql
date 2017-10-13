CREATE DATABASE IF NOT EXISTS jobsearch;
use jobsearch;

DROP TABLE IF EXISTS items;

CREATE TABLE items (
	itemcompname VARCHAR(30),
	itemdatetime VARCHAR(30),
	itemcontact VARCHAR(30),
	itemposition VARCHAR(30),
	itemcategory CHAR(2),
	itemdetails VARCHAR(160)
);
