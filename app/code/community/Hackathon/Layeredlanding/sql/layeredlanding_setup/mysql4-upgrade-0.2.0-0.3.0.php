<?php

$installer = $this;
 
$installer->startSetup();

$installer->run("
 
ALTER TABLE  `{$this->getTable('layeredlanding')}` ADD  `display_layered_navigation` BOOLEAN NOT NULL DEFAULT TRUE AFTER  `page_url` ,
ADD  `custom_layout_template` VARCHAR( 255 ) NOT NULL AFTER  `display_layered_navigation` ,
ADD  `custom_layout_update` TEXT NOT NULL AFTER  `custom_layout_template`;

    ");
 
$installer->endSetup();