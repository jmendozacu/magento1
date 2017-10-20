<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('tm_askit_item'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Id')
    ->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => NULL,
        ), 'Parent Id')
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => null,
        ), 'Item Id (product_id, cms page id or category id)')
    ->addColumn('item_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => TM_AskIt_Model_Item_Type::PRODUCT_ID,
        ), 'Item type id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => 0,
        ), 'Store Id')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        ), 'Customer ID')
    ->addColumn('customer_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 128, array(
        'nullable'  => true,
        'default'   => '',
        ), 'Customer Name')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 128, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Email')
    ->addColumn('text', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Email')
    ->addColumn('hint', Varien_Db_Ddl_Table::TYPE_SMALLINT, 6, array(
        // 'unsigned'  => true,
        'nullable'  => false,
        'default'   => 0,
        ), 'Hint')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        // 'unsigned'  => true,
        'nullable'  => false,
        'default'   => 1,
        ), 'Hint')
    ->addColumn('created_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')
    ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated At')
    ->addColumn('private', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        // 'unsigned'  => true,
        'nullable'  => false,
        'default'   => 0,
        ), 'Is Private')

    ->addIndex($installer->getIdxName('tm_askit_item', array('customer_id')),
        array('customer_id'))
    ->addIndex($installer->getIdxName('tm_askit_item', array('store_id')),
        array('store_id'))

    ->addForeignKey($installer->getFkName('tm_askit_item', 'customer_id', 'customer/entity', 'entity_id'),
         'customer_id', $installer->getTable('customer/entity'), 'entity_id',
            Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)
    ->addForeignKey($installer->getFkName('tm_askit_item', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

    ->setComment('Askit Item Table');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('tm_askit_vote'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Id')
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'unsigned'  => true,
        'nullable'  => true,
        'default'   => null,
        ), 'Item Id (product_id, cms page id or category id)')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        ), 'Customer ID')

    ->addIndex($installer->getIdxName('tm_askit_vote', array('item_id')),
        array('item_id'))
    ->addIndex($installer->getIdxName('tm_askit_vote', array('customer_id')),
        array('customer_id'))

    ->addForeignKey($installer->getFkName('tm_askit_vote', 'item_id', 'tm_askit_item', 'id'),
        'item_id', $installer->getTable('tm_askit_item'), 'id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('tm_askit_vote', 'customer_id', 'customer/entity', 'entity_id'),
         'customer_id', $installer->getTable('customer/entity'), 'entity_id',
            Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_SET_NULL)


    ->setComment('Askit Vote Table');
$installer->getConnection()->createTable($table);


$installer->endSetup();
