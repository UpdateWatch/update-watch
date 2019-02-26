CREATE DATABASE update_watch CHARACTER SET utf8 COLLATE utf8_swedish_ci;

USE update_watch;

CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET utf8;

CREATE TABLE `watchers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner` TEXT NOT NULL, /* email address */
  `backend` TEXT NOT NULL,
  `subject` TEXT NOT NULL, /* FIXME: return `null` in data.php if it's 'default'. or something like that */
  `url` TEXT NULL,

  `latest_version_number` INT NOT NULL,
  `latest_version_text` TEXT NOT NULL,
  `latest_version_url` TEXT NULL,

  `running_version_number` INT NOT NULL,
  `running_version_text` TEXT NOT NULL,
  `running_version_url` TEXT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET utf8;