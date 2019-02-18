CREATE DATABASE update_watch CHARACTER SET utf8 COLLATE utf8_swedish_ci;

USE update_watch;

CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET utf8;
