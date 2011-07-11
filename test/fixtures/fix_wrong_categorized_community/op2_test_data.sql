DROP TABLE IF EXISTS `c_commu_category_parent`;

CREATE TABLE `c_commu_category_parent` (
  `c_commu_category_parent_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`c_commu_category_parent_id`),
  KEY `sort_order` (`sort_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `c_commu_category`;

CREATE TABLE `c_commu_category` (
  `c_commu_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `c_commu_category_parent_id` int(11) NOT NULL DEFAULT '0',
  `is_create_commu` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`c_commu_category_id`),
  KEY `c_commu_category_parent_id` (`c_commu_category_parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `c_commu`;

CREATE TABLE `c_commu` (
  `c_commu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `c_member_id_admin` int(11) NOT NULL DEFAULT '0',
  `c_member_id_sub_admin` int(11) NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  `c_commu_category_id` int(11) NOT NULL DEFAULT '0',
  `r_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `r_date` date NOT NULL DEFAULT '0000-00-00',
  `image_filename` text NOT NULL,
  `is_send_join_mail` tinyint(1) NOT NULL DEFAULT '1',
  `is_regist_join` tinyint(1) NOT NULL DEFAULT '0',
  `u_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_admit` enum('public','auth') NOT NULL DEFAULT 'public',
  `is_open` enum('public','member') NOT NULL DEFAULT 'public',
  `is_topic` enum('member','admin_only','public') NOT NULL DEFAULT 'member',
  `is_comment` enum('member','public') NOT NULL DEFAULT 'member',
  `topic_authority` enum('public','admin_only') NOT NULL DEFAULT 'public',
  `public_flag` enum('public','auth_public','auth_sns','auth_commu_member') NOT NULL DEFAULT 'public',
  PRIMARY KEY (`c_commu_id`),
  KEY `c_commu_category_id` (`c_commu_category_id`),
  KEY `c_member_id_admin` (`c_member_id_admin`),
  KEY `r_datetime` (`r_datetime`),
  KEY `c_commu_category_id_r_datetime` (`c_commu_category_id`,`r_datetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO c_commu_category_parent VALUES (1, 'parent1', 10);
INSERT INTO c_commu_category_parent VALUES (2, 'parent2', 20);

INSERT INTO c_commu_category VALUES (1, 'category1', 10, 1, 1);
INSERT INTO c_commu_category VALUES (2, 'category2', 20, 1, 1);
INSERT INTO c_commu_category VALUES (3, 'category3', 30, 2, 1);

INSERT INTO c_commu VALUES(1, "communityA", 1, 0, "", 1, NOW(), NOW(), "", 1, 0, NOW(), "public", "public", "member", "member", "public", "public");
INSERT INTO c_commu VALUES(2, "communityB", 1, 0, "", 2, NOW(), NOW(), "", 1, 0, NOW(), "public", "public", "member", "member", "public", "public");
INSERT INTO c_commu VALUES(3, "communityC", 1, 0, "", 3, NOW(), NOW(), "", 1, 0, NOW(), "public", "public", "member", "member", "public", "public");
INSERT INTO c_commu VALUES(4, "communityD", 1, 0, "", 1, NOW(), NOW(), "", 1, 0, NOW(), "public", "public", "member", "member", "public", "public");
INSERT INTO c_commu VALUES(5, "communityE", 1, 0, "", 1, NOW(), NOW(), "", 1, 0, NOW(), "public", "public", "member", "member", "public", "public");

INSERT INTO c_commu VALUES(6, "communityG", 1, 0, "", 1, NOW(), NOW(), "", 1, 0, NOW(), "public", "public", "member", "member", "public", "public");
