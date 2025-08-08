<?php

class Sumo_Sumo_Model_Resource_Sumodbdata extends Mage_Core_Model_Resource_Db_Abstract {

	protected function _construct() {
		$this->_init('sumo_sumo/sumodbdata', 'option_id');
	}

}
