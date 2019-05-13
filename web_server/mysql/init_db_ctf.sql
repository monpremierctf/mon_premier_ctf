CREATE DATABASE dbctf;
USE dbctf;
CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, login VARCHAR(45) NULL, passwd VARCHAR(45) NULL, mail VARCHAR(45) NULL, pseudo VARCHAR(45) NULL, UID VARCHAR(45) NULL, PRIMARY KEY (id));
insert into users (login,passwd) values ('admin',md5('admin'));
insert into users (login,passwd) values ('user',md5('user'));

