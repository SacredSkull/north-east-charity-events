DROP DATABASE IF EXISTS north_east_charity;
DROP USER IF EXISTS 'northeast'@'%';
CREATE DATABASE north_east_charity;
CREATE USER 'northeast'@'%' IDENTIFIED BY 'charity';
CREATE USER 'root'@'%' IDENTIFIED BY 'vagrant';
GRANT ALL PRIVILEGES ON north_east_charity.* TO 'northeast'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
FLUSH PRIVILEGES;
