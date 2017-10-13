CREATE DATABASE IF NOT EXISTS jobsearch;
use jobsearch;

DROP TABLE IF EXISTS contacts;

CREATE TABLE contacts (
   contactname VARCHAR(30),
   contactcomp VARCHAR(30),
   contactjobtype CHAR(1),
   contactemail CHAR(30),
   contactphone1 VARCHAR(25),
   contacttype1 CHAR(6),
   contactphone2 VARCHAR(25),
   contacttype2 CHAR(6),
   contactphone3 VARCHAR(25),
   contacttype3 CHAR(6) );

INSERT INTO contacts  SELECT contactname, contactcomp, contactjobtype, contactemail, contactphone, 'office', contactfax, '', '', '' from contacts;