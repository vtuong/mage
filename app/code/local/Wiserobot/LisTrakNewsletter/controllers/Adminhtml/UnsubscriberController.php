<?php

class Wiserobot_LisTrakNewsletter_Adminhtml_UnsubscriberController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		
	  	require_once './app/code/local/Wiserobot/LisTrakNewsletter/Model/UnSubscribers.php';
	}

}