<?php
class Wiserobot_LisTrakNewsletter_EmailcaptureController extends Mage_Core_Controller_Front_Action {

	public function indexAction(){
		try {
            $email = $this->getRequest()->getParam('email');
            if (Zend_Validate::is($email, 'EmailAddress')) {
            	$res=Mage::getModel("listraknewsletter/api")->subscribeEmail($email, Mage::app()->getStore()->getStoreId());
                // echo "email=$email";
                // Mage::log($email,null,'capture.log');
            }
        } catch (Exception $e) {
            Mage::log($e,null,'listrak.log');
        }
    }

}
