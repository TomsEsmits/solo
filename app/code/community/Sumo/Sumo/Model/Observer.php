<?php

class Sumo_Sumo_Model_Observer {

    public function sumo_addCustomHandles($observer) {
		$update = $observer->getLayout()->getUpdate();
        $update->addHandle('sumo_sumo_new_handle');
     }
 }