-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Generation Time: May 03, 2005 at 08:03 AM
-- Server version: 4.1.9
-- PHP Version: 4.3.10
-- 
-- --------------------------------------------------------

-- 
-- Change all instances of "river" to whatever
-- Table Prefix you are using as defined
-- in your "river_config.php" file
-- --------------------------------------------------------

-- Table structure for table `river_emergency`
-- 

CREATE TABLE IF NOT EXISTS `river_emergency` (
  `id_key` int(11) NOT NULL auto_increment,
  `vol_id` int(11) NOT NULL default '0',
  `emerg_name_1` text,
  `emerg_rel_1` text,
  `emerg_phone_1` varchar(10) default NULL,
  `emerg_name_2` text,
  `emerg_rel_2` text,
  `emerg_phone_2` varchar(10) default NULL,
  `emerg_medic` text,
  `emerg_submit` text NOT NULL,
  `create_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=135 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `river_hours`
-- 

CREATE TABLE IF NOT EXISTS `river_hours` (
  `id_key` int(11) NOT NULL auto_increment,
  `vol_id` int(11) NOT NULL default '0',
  `proj_id` int(11) NOT NULL default '0',
  `shift_length` float NOT NULL default '0',
  `shift_id` int(11) NOT NULL default '0',
  `act_hours` float NOT NULL default '0',
  `notes` text NOT NULL,
  `create_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=978 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `river_projects`
-- 

CREATE TABLE IF NOT EXISTS `river_projects` (
  `id_key` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `company` text NOT NULL,
  `address` text,
  `city` text,
  `state` text,
  `zip_code` text,
  `sp_name` text NOT NULL,
  `proj_date` text NOT NULL,
  `proj_time` time default NULL,
  `proj_length` int(11) default NULL,
  `proj_shifts` int(11) default NULL,
  `description` longtext,
  `direction` text,
  `tools` text,
  `num_volunteers` int(11) default NULL,
  `Submit` text NOT NULL,
  `finalized` binary(1) NOT NULL default '0',
  `num_child_serv` int(11) NOT NULL default '0',
  `num_serv` int(11) NOT NULL default '0',
  `num_sp` int(11) NOT NULL default '0',
  `create_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `river_sessions`
-- 

CREATE TABLE IF NOT EXISTS `river_sessions` (
  `autoid` int(11) NOT NULL auto_increment,
  `sid` varchar(100) NOT NULL default '',
  `data` text,
  `addr` varchar(100) NOT NULL default '',
  `opened` int(14) default NULL,
  `expire` int(14) default NULL,
  PRIMARY KEY  (`autoid`),
  UNIQUE KEY `autoid` (`autoid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=188 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `river_users`
-- 

CREATE TABLE IF NOT EXISTS `river_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` text NOT NULL,
  `pw` text NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `river_volunteers`
-- 

CREATE TABLE IF NOT EXISTS `river_volunteers` (
  `id_key` int(11) NOT NULL auto_increment,
  `first_name` text NOT NULL,
  `middle_name` text NOT NULL,
  `last_name` text NOT NULL,
  `company` text,
  `address` text,
  `city` text,
  `state` text,
  `zip` text,
  `phone1` varchar(10) NOT NULL default '',
  `phone2` varchar(10) default NULL,
  `email` text,
  `birthdate` text,
  `consent_form` char(1) default NULL,
  `photo_form` char(1) default NULL,
  `Submit` text NOT NULL,
  `create_date` datetime default NULL,
  PRIMARY KEY  (`id_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2094 ;

-- --------------------------------------------------------

