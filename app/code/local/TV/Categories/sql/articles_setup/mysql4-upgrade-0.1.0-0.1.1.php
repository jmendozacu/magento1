<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE {$this->getTable('articles')}
CHANGE COLUMN `long_desc` `long_desc` text NULL,
ADD COLUMN `sub_title` VARCHAR(45) NOT NULL AFTER `title`;
");
$installer->endSetup();

/*$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->changeColumn($installer->getTable('articles'), 'long_desc', 'long_desc', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        
    )
    )
    ->addColumn($installer->getTable('articles'), 'sub_title', array(
        'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
        'nullable' => false,
        'comment' => 'Sub title'
    )
);
$installer->endSetup();*/
?>

