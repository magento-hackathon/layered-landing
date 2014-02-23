<?php

$installer = $this;
 
$installer->startSetup();

$installer->run("
  ALTER TABLE  `{$this->getTable('layeredlanding')}` ADD `display_in_top_navigation` TINYINT(1) NOT NULL DEFAULT 0 AFTER `display_layered_navigation`
");

$installer->endSetup();