
-- ------
-- BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
-- Trickerion implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

ALTER TABLE `player` ADD `player_shards` int(10) NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `player_coins` int(10) NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `player_initiative` int(10) NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `player_color_name` varchar(32) NOT NULL DEFAULT 'unknown';

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `move_id` int(10) NOT NULL,
  `table` varchar(32) NOT NULL,
  `primary` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `affected` JSON,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `trick` (
  `trick_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trick_type` varchar(32) NOT NULL,
  `trick_location` varchar(32) NOT NULL,
  `trick_state` int(10),
  `player_id` int(10),
  `trick_suit` varchar(16) NOT NULL,
  PRIMARY KEY (`trick_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `performance` (
  `performance_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `performance_type` varchar(32) NOT NULL,
  `performance_location` varchar(32) NOT NULL,
  `performance_state` int(10),
  PRIMARY KEY (`performance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `assignment` (
  `assignment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assignment_type` varchar(32) NOT NULL,
  `assignment_location` varchar(32) NOT NULL,
  `assignment_state` int(10),
  `player_id` int(10),
  PRIMARY KEY (`assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prophecy` (
  `prophecy_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prophecy_type` varchar(32) NOT NULL,
  `prophecy_location` varchar(32) NOT NULL,
  `prophecy_state` int(10),
  PRIMARY KEY (`prophecy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `magician` (
  `magician_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `magician_type` varchar(32) NOT NULL,
  `magician_location` varchar(32) NOT NULL,
  `magician_state` int(10),
  `player_id` int(10),
  PRIMARY KEY (`magician_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `trick_marker` (
  `trick_marker_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trick_marker_location` varchar(32) NOT NULL,
  `trick_marker_state` int(10),
  `player_id` int(10),
  `trick_marker_suit` varchar(32) NOT NULL,
  `trick_id` int(10),
  PRIMARY KEY (`trick_marker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `character` (
  `character_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `character_location` varchar(32) NOT NULL,
  `character_state` int(10),
  `player_id` int(10),
  `character_type` varchar(32) NOT NULL,
  PRIMARY KEY (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `poster` (
  `poster_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poster_location` varchar(32) NOT NULL,
  `poster_state` int(10),
  `player_id` int(10),
  PRIMARY KEY (`poster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `component` (
  `component_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `component_location` varchar(32) NOT NULL,
  `component_state` int(10),
  `player_id` int(10),
  `component_type` varchar(32) NOT NULL,
  `component_count` int(10),
  PRIMARY KEY (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
