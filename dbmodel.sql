
-- ------
-- BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
-- Trickerion implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

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
  `trick_symbol_marker` varchar(16) NOT NULL,
  PRIMARY KEY (`trick_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
