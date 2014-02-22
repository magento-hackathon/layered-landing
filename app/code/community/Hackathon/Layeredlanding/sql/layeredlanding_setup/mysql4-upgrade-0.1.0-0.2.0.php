<?php

$installer = $this;
 
$installer->startSetup();

$installer->run("
 
ALTER TABLE  `{$this->getTable('layeredlanding')}` ADD  `page_url` VARCHAR( 255 ) NOT NULL AFTER  `page_description`;

ALTER TABLE `{$this->getTable('layeredlanding')}` ADD UNIQUE KEY `uk_url_store` (`page_url`, `store_ids`);

    ");
 
$installer->endSetup();