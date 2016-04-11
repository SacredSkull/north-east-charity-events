DROP DATABASE IF EXISTS north_east_charity;
DROP USER IF EXISTS 'northeast'@'%';
CREATE DATABASE north_east_charity;
CREATE USER 'northeast'@'%' IDENTIFIED BY 'charity';
GRANT ALL PRIVILEGES ON north_east_charity.* TO 'northeast'@'%';
FLUSH PRIVILEGES;
