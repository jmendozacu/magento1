<?php

/*
  $installer = $this;
  $installer->startSetup();
  $installer->run("
  -- DROP TABLE IF EXISTS {$this->getTable('articles')};
  CREATE TABLE {$this->getTable('articles')} (
  `articles_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `short_desc` text NOT NULL default '',
  `long_desc` text NOT NULL default '',
  `status` tinyint(2) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`articles_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ");
  $installer->endSetup();
 */


$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()->newTable($installer->getTable('articles'))
        ->addColumn('articles_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
                ), 'Article ID')
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
            'default' => '',
                ), 'Title')
        ->addColumn('short_desc', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => false,
            'default' => '',
                ), 'Short Desc')
        ->addColumn('long_desc', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => false,
            'default' => '',
                ), 'Long Desc')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 2, array(
            'nullable' => false,
            'default' => '0',
                ), 'Status')
        ->addColumn('created_time', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
            'nullable' => true,
            'default' => null,
                ), 'Created Date')
        ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
            'nullable' => true,
            'default' => null,
                ), 'Update Date')
        ->setComment('Articles table');
$installer->getConnection()->createTable($table);
$installer->endSetup();





