<?php
class Sumo_Sumo_Block_Sumo extends Mage_Core_Block_Template {
    public function __construct() {
        $this->_controller = 'Sumo_Sumo_Adminhtml_IndexController';
        $this->_blockGroup = 'sumo';
        $this->_title = 'Sumo';
        $this->_headerText = Mage::helper('sumo')->__('Sumo');
        parent::__construct();
        $dbTablePrefix = Mage::getConfig()->getTablePrefix();
        $this->tableName=$dbTablePrefix."sumo_module";
    }

    public function setSessionData($key, $value) {
        return Mage::getSingleton('core/session')->setData($key, $value);
    }

    public function getSessionData($key) {
        return Mage::getSingleton('core/session')->getData($key);
    }

    public function updateCancelTime(){
        return Mage::getSingleton('core/session')->getFormKey();
    }

    public function getSiteID() {
        $resource = new Mage_Core_Model_Resource();  
        $read = $resource->getConnection('core_read');  
        $select = $read->select()
                       ->from($this->tableName)
                       ->where('option_name = ?', 'sumo_site_id');  
        $result=$read->fetchAll($select);  

        return $result[0]['option_value'];
    }


    public function updateSiteID($siteID) {

        $resource = new Mage_Core_Model_Resource();  
        $write = $resource->getConnection('core_write');  
        $write->getConnection()->query("DELETE FROM ".$this->tableName." WHERE option_name = 'sumo_site_id'");    

        $write->getConnection()->query("INSERT INTO ".$this->tableName." SET option_name = 'sumo_site_id', option_value='".$siteID."'");

        return true;
    }

    public function clearCache() {
        //clear cache
        //Mage::app()->getCacheInstance()->flush(); 
        $allTypes = Mage::app()->useCache();
        foreach($allTypes as $type => $value) {
            Mage::app()->getCacheInstance()->cleanType($type);
            Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $type));
        }
    } 


}
