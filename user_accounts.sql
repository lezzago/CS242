CREATE TABLE IF NOT EXISTS `user_accounts` (
  `user_id` int(6) AUTO_INCREMENT,
  `user_name` varchar(24),
  `password` char(255),
  `email` char(200),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
)
