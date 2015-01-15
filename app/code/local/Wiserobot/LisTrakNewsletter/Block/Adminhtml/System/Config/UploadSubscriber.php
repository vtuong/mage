<?php

class Wiserobot_LisTrakNewsletter_Block_Adminhtml_System_Config_UploadSubscriber extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
      $params = $this->getRequest()->getParams();
      if (!isset($params['store'])){
          if (!isset($params['website'])) {
              $store = Mage::app()->getStore();
          } else {
              $website = Mage::getModel('core/website')->load($params['website']);
              $store = $website->getDefaultStore();
          }
      } else {
          $store = Mage::app()->getStore($params['store']);
      }

      $link='<a target="_blank" href="/listraknewsletter/index/uploadSubscribers/store/'.$store->getStoreId().'">Upload Subscribers</a>';
      return $link;
    }

}
