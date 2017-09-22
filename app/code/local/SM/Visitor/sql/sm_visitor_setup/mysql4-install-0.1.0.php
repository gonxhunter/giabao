<?php
/**
 * Created by PhpStorm.
 * User: chutienphuc
 * Date: 20/09/2017
 * Time: 18:07
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('sm_visitor/count')};
CREATE TABLE {$this->getTable('sm_visitor/count')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `IP` varchar(20) NOT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)    
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->endSetup();