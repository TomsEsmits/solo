<?php

class Sumo_Sumo_Model_Sumodbdata extends Mage_Core_Model_Abstract {

	protected function _construct() {
		$this->_init('sumo_sumo/sumodbdata');
	}

    public function upgradeFromManualTag() {
       
        $siteID="";
        $dbTablePrefix = Mage::getConfig()->getTablePrefix();

        $resource = new Mage_Core_Model_Resource();  
        $read = $resource->getConnection('core_read');  
        $write = $resource->getConnection('core_write'); 
        $select = $read->select()
                       ->from($dbTablePrefix.'core_config_data')
                       ->where("`value` like '%load.sumome.com%' or `value` like '%load.sumo.com%'");  
        $existingHtmlHead=$read->fetchAll($select); 
        
        if ($existingHtmlHead) foreach ($existingHtmlHead as $key => $value) {
            $htmlHead=$value['value'];
            $manualTagInfo=$this->getManualTag($htmlHead);

            if (trim($manualTagInfo['siteID'])) {
                $siteID=$manualTagInfo['siteID'];
                $newHtmlHead=$manualTagInfo['newHtmlHead'];
                
                $write->getConnection()->query("UPDATE ".$dbTablePrefix."core_config_data SET  `value`='".$newHtmlHead."' where config_id='".$value['config_id']."'");
            }

        }

        return $siteID;

    }

    public function getManualTag($subject) {

         $pattern='/<script\\b[^>]*>(.*?)<\\/script>/i';
         $scriptTag = preg_match($pattern, $subject, $matches);

         if ($matches) foreach ($matches as $scriptLineKey => $scriptLine) {
            if (substr_count($scriptLine, 'load.sumome.com')>0 || substr_count($scriptLine, 'load.sumo.com')>0) {

                $dom = new \DOMDocument('1.0', 'utf-8');
                $dom->loadHTML($scriptLine);
                $nodes = $dom->getElementsByTagName('script');

                $siteID=$nodes->item(0)->getAttribute('data-sumo-site-id');

                //regex is too risky, switching to a static find
                $scriptLineFind[0]="<script src='//load.sumome.com/' data-sumo-site-id='".$siteID."' data-magento async='async'></script>";
                $scriptLineFind[1]='<script src="//load.sumome.com/" data-sumo-site-id="'.$siteID.'" data-magento async="async"></script>';
                $scriptLineFind[2]='<script src="//load.sumome.com/" data-sumo-site-id="'.$siteID.'" async="async"></script>';
                $scriptLineFind[3]="<script src='//load.sumome.com/' data-sumo-site-id='".$siteID."' async='async'></script>";

                $scriptLineFind[4]="<script src='//load.sumo.com/' data-sumo-site-id='".$siteID."' data-magento async='async'></script>";
                $scriptLineFind[5]='<script src="//load.sumo.com/" data-sumo-site-id="'.$siteID.'" data-magento async="async"></script>';
                $scriptLineFind[6]='<script src="//load.sumo.com/" data-sumo-site-id="'.$siteID.'" async="async"></script>';
                $scriptLineFind[7]="<script src='//load.sumo.com/' data-sumo-site-id='".$siteID."' async='async'></script>";

                $newContents=str_replace($scriptLineFind,"",$subject);

                return array('siteID'=>$siteID,'newHtmlHead'=>$newContents);

            }
         }
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
