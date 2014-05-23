DROP TABLE IF EXISTS `manager_userlog`;
CREATE TABLE `manager_userlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user` varchar(50) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;

-- ----------------------------
-- Table structure for `manager_users`
-- ----------------------------
DROP TABLE IF EXISTS `manager_users`;
CREATE TABLE `manager_users` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `permission_id` tinyint(2) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=2;

-- ----------------------------
-- Records of manager_users
-- ----------------------------
INSERT INTO `manager_users` VALUES ('1', '0', 'admin', 'M83Cc/awAmMGQ1qL+BfZ2yY5ckThHDQPXOFT2642xXpo37KZOq4XNTDJAQXOsPQV6+MrOWNx9zktzlmQLlSkIA==', '1');