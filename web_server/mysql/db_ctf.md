DATABASE dbctf;



 TABLE users (
     id INT NOT NULL AUTO_INCREMENT, 
     UID VARCHAR(45) NULL, 
     login VARCHAR(45) NULL, 
     passwd VARCHAR(45) NULL, 
     mail VARCHAR(45) NULL, 
     pseudo VARCHAR(45) NULL,
     status VARCHAR(45) NULL,
     PRIMARY KEY (id));


=> ('admin',md5('_RANDOM16_'),'_RANDOM16_');




 TABLE flags (
     id INT NOT NULL AUTO_INCREMENT, 
     UID VARCHAR(45) NULL, 
     CHALLID VARCHAR(45) NULL, 
     fdate datetime, 
     isvalid BOOLEAN, 
     flag VARCHAR(45) , 
     PRIMARY KEY (id));



CREATE TABLE feedbacks (
    id INT NOT NULL AUTO_INCREMENT, 
    login VARCHAR(45) NULL, 
    name VARCHAR(45) NULL, 
    mail VARCHAR(45) NULL, 
    txt VARCHAR(2000) NULL, 
    UID VARCHAR(45) NULL, 
    PRIMARY KEY (id));


CREATE TABLE logs (
    id INT NOT NULL AUTO_INCREMENT, 
    fdate datetime, 
    txt VARCHAR(2000) NULL, 
    PRIMARY KEY (id));


CREATE USER 'ctfuser'@'%' IDENTIFIED BY '_RANDOM16_';
GRANT SELECT, INSERT, UPDATE ON dbctf.* TO 'ctfuser'@'%';
