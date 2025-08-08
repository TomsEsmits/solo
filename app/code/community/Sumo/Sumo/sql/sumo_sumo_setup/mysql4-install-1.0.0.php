<?php
$installer = $this;

$installer->startSetup();

$dbTablePrefix = Mage::getConfig()->getTablePrefix();

if ($installer->getConnection()->isTableExists('sumo_module') != true && $installer->getConnection()->isTableExists($dbTablePrefix.'sumo_module') != true) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('sumo_sumo/sumodbdata'))
        ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
            ), 'Option ID')
        ->addColumn('option_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
            ), 'Option Name')
        ->addColumn('option_value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
            ), 'Option Value');
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();
