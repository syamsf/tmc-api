CREATE DATABASE IF NOT EXISTS `command_db`;
CREATE DATABASE IF NOT EXISTS `query_db`;

CREATE USER 'root'@'localhost' IDENTIFIED BY 'randompassword';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
