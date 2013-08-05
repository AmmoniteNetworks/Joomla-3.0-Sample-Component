SET NAMES utf8;
-- ----------------------------
--  Table structure for `#_sample_items`
-- ----------------------------
DROP TABLE IF EXISTS `#_sample_items`;
CREATE TABLE `fff_sample_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `description` varchar(25) NOT NULL,
  `file` varchar(25) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `access` varchar(255) NOT NULL,
  `created_time` datetime NOT NULL,
  `modified_time` datetime NOT NULL,
  `created` datetime NOT NULL,
  `catid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;