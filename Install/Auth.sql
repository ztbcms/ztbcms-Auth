DROP TABLE IF EXISTS `cms_auth_access_token`;
CREATE TABLE `cms_auth_access_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(32) NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL,
  `username` char(32) NOT NULL,
  `access_token` varchar(128) NOT NULL DEFAULT '',
  `platform` varchar(16) NOT NULL DEFAULT '',
  `expired_time` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `login_code` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `access_token` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `cms_auth_open_platform`;
CREATE TABLE `cms_auth_open_platform` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(32) NOT NULL,
  `app_secret` varchar(32) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `add_time` int(11) NOT NULL,
  `is_allow_auth` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
