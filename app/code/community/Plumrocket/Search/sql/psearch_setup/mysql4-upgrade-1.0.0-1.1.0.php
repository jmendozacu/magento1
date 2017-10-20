<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Search
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */


$installer = $this;
$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `{$this->getTable('plumrocket_search_fulltext')}` (
      `product_id` int(10) unsigned NOT NULL COMMENT 'Product ID',
      `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store ID',
      `attribute_id` int(10) NOT NULL COMMENT 'Attribute ID',
      `priority` smallint(5) unsigned NOT NULL,
      `data_index` longtext COMMENT 'Data index',
      `fulltext_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entity ID',
      PRIMARY KEY (`fulltext_id`),
      UNIQUE KEY `idx_product_store_attribute` (`product_id`,`store_id`,`attribute_id`),
      FULLTEXT KEY `idx_fulltext_data_index` (`data_index`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Plumrocket search result table';
");

// Add priority to attribute.
try {
    $installer->run("
        ALTER TABLE `{$this->getTable('catalog_eav_attribute')}` ADD `psearch_priority` SMALLINT(5) UNSIGNED NOT NULL;
    ");
} catch (Exception $e) {}

$installer->endSetup();