<?php

require_once __DIR__.'/../../web/sumome-plugin/classes/class_sumo.phtml';
use mps\Magento_Plugin_Sumo;
$this->magento_plugin_sumome = new Magento_Plugin_Sumo('');

$dbTablePrefix = Mage::getConfig()->getTablePrefix();
$tableName=$dbTablePrefix.'sumo_module';
$siteID=""; 


$resource = new Mage_Core_Model_Resource();  
$read = $resource->getConnection('core_read');  
$write = $resource->getConnection('core_write'); 

$select = $read->select()
               ->from($tableName)
               ->where('option_name = ?', 'sumo_site_id');  
$result=$read->fetchAll($select);  
if ($result && $result[0]['option_name']) $siteID=$result[0]['option_value'];


//check for existing manual tag.  if found, remove it and use siteID for sumo module
$modObj=Mage::getModel('sumo_sumo/sumodbdata');
$siteID=$modObj->upgradeFromManualTag();


//if no existing tag, create a new random tag
if (!trim($siteID)) {
    $siteID=$this->magento_plugin_sumome->sumome_generate_site_id();
}


//if no site id, or site id is less than 64 chars
if ($result && $result[0]['option_name']=="sumo_site_id" && strlen(trim($result[0]['option_value']))!=64) {
    $result='';
    $write->getConnection()->query("DELETE FROM ".$tableName." WHERE option_name = 'sumo_site_id'");       
}

if (!$result) {
	//$write->getConnection()->query("INSERT INTO ".$tableName." SET option_name = 'sumo_site_id', option_value='".$siteID."'")

	$sumoDbDataArray=array('option_name'=>'sumo_site_id','option_value'=>$siteID);

	Mage::getModel('sumo_sumo/sumodbdata')
	->setData($sumoDbDataArray)
	->save();

}

//clear cache
$modObj->clearCache();





