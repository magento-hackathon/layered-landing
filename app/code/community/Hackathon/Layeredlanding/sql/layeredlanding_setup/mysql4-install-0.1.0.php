<?php

$installer = $this;
 
$installer->startSetup();

$installer->run("
 
CREATE TABLE IF NOT EXISTS `{$this->getTable('layeredlanding')}` (
  `layeredlanding_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `meta_title` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_description` text NOT NULL,
  `store_ids` varchar(255) NOT NULL DEFAULT '0',
  `category_ids` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layeredlanding_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `{$this->getTable('layeredlanding/attributes')}` (
  `layeredlanding_attributes_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `layeredlanding_id` int(11) NOT NULL DEFAULT '0',
  `attribute_id` varchar(255) NOT NULL,
  `value` varchar(20) NOT NULL,
  PRIMARY KEY (`layeredlanding_attributes_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

    ");
 
$installer->endSetup();