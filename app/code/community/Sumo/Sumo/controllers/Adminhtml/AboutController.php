<?php

class Sumo_Sumo_Adminhtml_AboutController extends Mage_Adminhtml_Controller_Action {

  /**
  * Admin controller index action
  *
  * @access public
  * @return void
  */


   protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('sumo_admin/adminhtml_about')
            ->_title('Sumo');
        return $this;
   }


  public function indexAction() {

      if ($this->getRequest()->getPost('siteID') && $this->getRequest()->getPost('form_key')) {
        $post['siteID'] = $this->getRequest()->getPost('siteID');
        $post['form_key'] = $this->getRequest()->getPost('form_key');
      }
      //change site id
      if (isset($post['siteID']) && isset($post['form_key'])) {
        $blockObj=Mage::getBlockSingleton('sumo/sumo');
        $blockObj->updateSiteID($post['siteID']);
        $blockObj->setSessionData('sumome_site_id',$post['siteID']);

        //clear cache
        $blockObj->clearCache();
     
      }

      $this->getRequest()->setParam('page','about');

      $this->loadLayout(array('default'));
      $this->renderLayout();


  }  
  
  protected function _isAllowed(){
      return true;
  }
}
