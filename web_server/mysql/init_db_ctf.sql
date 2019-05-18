CREATE DATABASE dbctf;
USE dbctf;
CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, login VARCHAR(45) NULL, passwd VARCHAR(45) NULL, mail VARCHAR(45) NULL, pseudo VARCHAR(45) NULL, UID VARCHAR(45) NULL, PRIMARY KEY (id));
insert into users (login,passwd) values ('admin',md5('admin'));
insert into users (login,passwd) values ('user',md5('user'));
CREATE TABLE flags (id INT NOT NULL AUTO_INCREMENT, UID VARCHAR(45) NULL, CHALLID VARCHAR(45) NULL, fdate datetime, isvalid BOOLEAN, flag VARCHAR(45) , PRIMARY KEY (id));
insert into flags (UID,CHALLID, fdate, isvalid, flag) values ('user1','chall1', NOW(), FALSE, 'flagxx');
insert into flags (UID,CHALLID, fdate, isvalid, flag) values ('user1','chall1', NOW(), TRUE, 'flag1');
