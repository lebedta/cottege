	CREATE TABLE IF NOT EXISTS `#__djcf_categories` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(100) default NULL,
	  `parent_id` int(11) NOT NULL,
	  `price` int(11) NOT NULL default '0',
	  `description` text NOT NULL,
	  `icon_url` varchar(255) NOT NULL,
	  `ordering` int(11) NOT NULL default '0',
	  `published` int(11) NOT NULL default '1',
	  `autopublish` int(11) NOT NULL default '0',
	  `metakey` text NOT NULL,
	  `metadesc` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL,
	  `label` varchar(255) NOT NULL,
	  `type` varchar(255) NOT NULL,
	  `values` text NOT NULL,
	  `default_value` varchar(255) NOT NULL,
	  `params` text NOT NULL,
	  `in_search` int(11) NOT NULL default '0',
	  `search_type` varchar(255) NOT NULL,
	  `search_value1` text NOT NULL,
	  `search_value2` text NOT NULL,
	  `published` int(11) NOT NULL default '0',
	  `required` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields_values` (
	  `id` int(11) NOT NULL auto_increment,
	  `field_id` int(11) NOT NULL,
	  `item_id` int(11) NOT NULL,
	  `value` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields_xref` (
	  `id` int(11) NOT NULL auto_increment,
	  `cat_id` int(11) NOT NULL,
	  `field_id` int(11) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_items` (
	  `id` int(11) NOT NULL auto_increment,
	  `cat_id` int(11) NOT NULL default '0',
	  `user_id` int(11) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `description` text,
	  `intro_desc` text,
	  `image_url` text,
	  `date_start` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  `date_exp` timestamp NOT NULL default '0000-00-00 00:00:00',
	  `date_mod` timestamp NOT NULL default '0000-00-00 00:00:00',
	  `display` int(11) NOT NULL default '0',
	  `special` int(11) NOT NULL default '0',
	  `paypal_token` varchar(12) NOT NULL,
	  `payed` int(11) NOT NULL default '0',
	  `notify` int(11) NOT NULL default '0',
	  `published` int(11) NOT NULL default '1',
	  `ordering` int(11) NOT NULL default '0',
	  `price` varchar(255) default NULL,
	  `contact` text,
	  `pay_type` varchar(255) default NULL,
	  `address` varchar(255) NOT NULL,
	  `region_id` int(11) NOT NULL default '0',
	  `exp_days` int(11) NOT NULL,
	  `promotions` text NOT NULL,
	  PRIMARY KEY  (`id`),
	  KEY `cat_id` (`cat_id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_itemsask` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `ip_address` varchar(50) NOT NULL,
	  `message` text NOT NULL,
	  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_items_abuse` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `message` text NOT NULL,
	  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_payments` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `method` varchar(255) NOT NULL,
	  `transaction_id` int(11) NOT NULL,
	  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  `status` varchar(255) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_regions` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL,
	  `parent_id` int(11) NOT NULL default '0',
	  `country` int(11) NOT NULL default '0',
	  `city` int(11) NOT NULL default '0',
	  `published` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_favourites` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	

	CREATE TABLE IF NOT EXISTS `#__djcf_days` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `days` int(11) NOT NULL,
	  `price` float(12,2) NOT NULL,
	  `points` int(11) NOT NULL,
	  `published` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
	INSERT INTO `#__djcf_days` (`id`, `days`, `price`, `points`, `published`) VALUES
		(1, 7, 0.00, 0, 1),
		(2, 14, 1.00, 0, 1),
		(3, 21, 2.00, 0, 1),
		(4, 30, 3.00, 0, 1);	
	

	CREATE TABLE IF NOT EXISTS `#__djcf_promotions` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `label` varchar(255) NOT NULL,
	  `description` varchar(255) NOT NULL,
	  `price` float(12,2) NOT NULL,
	  `points` int(11) NOT NULL,
	  `published` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	

	INSERT INTO `#__djcf_promotions` (`id`, `name`, `label`, `description`, `price`, `points`, `published`) VALUES
		(1, 'p_first', 'COM_DJCLASSIFIEDS_PROMOTION_FIRST', 'COM_DJCLASSIFIEDS_PROMOTION_FIRST_DESC', 1.00, 0, 1),
		(2, 'p_bold', 'COM_DJCLASSIFIEDS_PROMOTION_BOLD', 'COM_DJCLASSIFIEDS_PROMOTION_BOLD_DESC', 1.00, 0, 1),
		(3, 'p_border', 'COM_DJCLASSIFIEDS_PROMOTION_BORDER', 'COM_DJCLASSIFIEDS_PROMOTION_BORDER_DESC', 1.00, 0, 1),
		(4, 'p_bg', 'COM_DJCLASSIFIEDS_PROMOTION_BG', 'COM_DJCLASSIFIEDS_PROMOTION_BG_DESC', 1.00, 0, 1),
		(5, 'p_special', 'COM_DJCLASSIFIEDS_PROMOTION_SPECIAL', 'COM_DJCLASSIFIEDS_PROMOTION_SPECIAL_DESC', 1.00, 0, 1);