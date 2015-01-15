<?php
class Wiserobot_LisTrakNewsletter_IndexController extends Mage_Core_Controller_Front_Action {
    
    public function indexAction(){
		$this->loadLayout();     
		$this->renderLayout();
    }

    public function downloadsubscribersAction(){
		$api=Mage::getModel('listraknewsletter/api');
		$storeId=1;
		if($this->getRequest()->getParam('store')){
			$storeId=$this->getRequest()->getParam('store');
		}else{
			$storeId = Mage::app()->getStore()->getStoreId();
		}
		$contactlist=$api->ReportSubscribedContacts($storeId);
		$subscribers=array();
		$ltSubscribers=array();
		$page=2;
		while(!empty($contactlist->ReportSubscribedContactsResult->WSContactSubscriptionInfo)){
		    $subscribers=array_merge($subscribers,$contactlist->ReportSubscribedContactsResult->WSContactSubscriptionInfo);
		    $contactlist=$api->ReportSubscribedContacts($storeId,$page);
		    $page++;
		}
		$txt="";
		foreach ($subscribers as $ltSubscriber) {
			$ltSubscribers[]=trim(strtolower((string)$ltSubscriber->EmailAddress));
			$txt.=trim(strtolower((string)$ltSubscriber->EmailAddress))."\n";
		}
		
		// echo "<pre>";
		// 	// print_r($ltSubscribers);
		// 	echo $txt;
		// echo "</pre>";
		$this->_prepareDownloadResponse("subscribers.txt", $txt);
	}

	//get email customer up to subscriber listrak
	public function uploadSubscribersAction(){
		$api=Mage::getModel('listraknewsletter/api');
		$storeId=1;
		$txt="";
		$emails=array();
		if($this->getRequest()->getParam('store')){
			$storeId=$this->getRequest()->getParam('store');
		}else{
			$storeId = Mage::app()->getStore()->getStoreId();
		}

		// magento subscriber
		$mageSubscriberCollections=Mage::getModel('newsletter/subscriber')->getCollection()->addStoreFilter($storeId);
		foreach ($mageSubscriberCollections as $_mageSubscriber) {
			$email=trim(strtolower($_mageSubscriber->getEmail()));
			// $emails[]=$email;
			$txt.=$email."\n";
			$api->subscribeEmail($email,$storeId); 
			if($_mageSubscriber->getCustomerId()!=0){
				$customer = Mage::getModel("customer/customer"); 
				$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
				$customer->loadByEmail($email); 
				$res=Mage::getModel('listraknewsletter/subscriber')->setContactFromMappingInfo($customer->getData());
				// echo "<pre>";print_r($res);echo "</pre>";
				// echo "<br><br><br>";
			}
		}
		// echo "<pre>";print_r($emails);echo "</pre>";
		$this->_prepareDownloadResponse("magentoEmail_subscriberList.txt", $txt);
	}

}