-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_lws_news2facebook`
-- 

CREATE TABLE `tl_lws_news2facebook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `appid` varbinary(128) NOT NULL default '',
  `secret` varbinary(128) NOT NULL default '',
  `usersession` mediumtext NULL,
  `targetid` varbinary(128) NOT NULL default '',
  `authorid` varbinary(128) NOT NULL default '',
  `permissions` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_lws_news2facebook_news`
-- 

CREATE TABLE `tl_lws_news2facebook_news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `facebookid` varchar(255) NOT NULL default ''
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- 
-- Table `tl_news_archive`
-- 

CREATE TABLE `tl_news_archive` (
	`socialNews` char(1) NOT NULL default '',
	`socialNewsService` int(10) unsigned NOT NULL default '0',
	`facebookTeaserLength` int(3) unsigned NOT NULL default '0',
	`stdurl` varchar(255) NOT NULL default '',
	`defaultPic` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_news`
-- 

CREATE TABLE `tl_news` (
	`facebookTeaser` text NULL,
	`addSocial` char(1) NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
