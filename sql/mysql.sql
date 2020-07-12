-- phpMyAdmin SQL Dump
-- version 2.6.0-rc2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 09, 2004 at 02:54 PM
-- Server version: 4.0.22
-- PHP Version: 4.3.9
-- 
-- Database: `dev_xoops`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `xmline_categories`
-- 

CREATE TABLE `xmline_categories` (
  `category_id` 	int(8) 			unsigned NOT NULL auto_increment,
  `image` 			varchar(255) 	NOT NULL default '',
  `title` 			varchar(255) 	NOT NULL default '',
  `category_order` 	int(8) 			NOT NULL default '1',
  
  PRIMARY KEY  		(`category_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `xmline_categories`
-- 

INSERT INTO `xmline_categories` VALUES (1, '', 'XOOPS', 1);
INSERT INTO `xmline_categories` VALUES (2, '', 'News', 2);
INSERT INTO `xmline_categories` VALUES (3, '', 'XML', 3);
INSERT INTO `xmline_categories` VALUES (4, '', 'BLOG', 4);

-- --------------------------------------------------------

-- 
-- Table structure for table `xmline_digests`
-- 

CREATE TABLE `xmline_digests` (
  `digest_id` 		int(8) 			unsigned NOT NULL auto_increment,
  `category_id` 	int(8) 			unsigned NOT NULL default '1',
  `digest_order` 	int(8) 			NOT NULL default '1',
  
  `rss` 			varchar(255) 	NOT NULL default '',
  `online` 			int(1) 			NOT NULL default '1',
  `title` 			varchar(40) 	NOT NULL default '',
  `description` 	varchar(255) 	NOT NULL default '',
  `url` 			varchar(255) 	NOT NULL default '',
  `image` 			varchar(255) 	NOT NULL default '',
  `maxitems` 		int(4) 			unsigned NOT NULL default '0',
  `charset` 		varchar(40) 	NOT NULL default '',
  `charset_inter` 	varchar(40) 	NOT NULL default '',
  `updatetime` 		int(4) 			unsigned NOT NULL default '60',
  `lastupdate` 		int(11) 		unsigned NOT NULL default '0',
  `items` 			text,
  
  PRIMARY KEY  		(`digest_id`),
  KEY `category_id`	(`category_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `xmline_digests`
-- 

INSERT INTO `xmline_digests` VALUES (1, 1, 1, 'http://xoops.org/backend.php', 1, 'XOOPS', 'XOOPS Official', 'http://xoops.org', '', 20, '', '', 60, 0, NULL);
INSERT INTO `xmline_digests` VALUES (2, 1, 2, 'http://xoops.org.cn/backend.php', 1, 'XCN', 'XOOPS CHINA', 'http://xoops.org.cn', '', 20, '', '', 60, 0, NULL);
INSERT INTO `xmline_digests` VALUES (3, 1, 4, 'http://dev.xoops.org/backend.php', 1, 'DXO', 'Xoops Module Dev', 'http://dev.xoops.org/', '', 20, '', '', 60, 0, NULL);

INSERT INTO `xmline_digests` VALUES (4, 2, 1, 'http://sourceforge.net/export/rss_sfnews.php', 1, 'SFNEWS', 'Source forge news', 'http://sourceforge.net', '', 20, '', '', 60, 0, NULL);
INSERT INTO `xmline_digests` VALUES (5, 2, 2, 'http://www.sitepoint.com/recent.rdf', 1, 'SPNews', 'SitePoint News', 'http://www.sitepoint.com', '', 20, '', '', 60, 0, NULL);
INSERT INTO `xmline_digests` VALUES (6, 2, 3, 'http://rss.xinhuanet.com/rss/it.xml', 1, 'XINHUA', 'XinHua Net News', 'http://xinhuanet.com', '', 20, '', '', 60, 0, NULL);

INSERT INTO `xmline_digests` VALUES (7, 3, 1, 'http://www.oreillynet.com/meerkat/?_fl=atom&t=ALL&c=47', 1, 'XML', 'XML Atom Feed', 'http://xml.com', '', 20, '', '', 60, 0, NULL);
INSERT INTO `xmline_digests` VALUES (8, 3, 2, 'http://www.oreillynet.com/meerkat/?_fl=rss10&t=ALL&c=47', 1, 'XMLRDF', 'XML RDF 1.0 Feed', 'http://www.oreillynet.com', '', 20, '', '', 60, 0, NULL);

INSERT INTO `xmline_digests` VALUES (9, 4, 1, 'http://devteam.xoops.org/devlog/feeds/index.rss2', 1, 'XDEV', 'Xoops Core Dev', 'http://devteam.xoops.org/', '', 20, '', '', 60, 0, NULL);
INSERT INTO `xmline_digests` VALUES (10, 4, 2, 'http://www.sitepoint.com/blogs.rdf', 1, 'SPBLOG', 'SitePoint Blogs', 'http://www.sitepoint.com/', '', 20, '', '', 60, 0, NULL);
