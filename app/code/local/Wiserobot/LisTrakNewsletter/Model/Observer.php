<?php
/*
WiseRobot Co.,LTD **NOTICE OF LICENSE**
This source file is subject to the EULA that is bundled with this package in the file LICENSE.pdf. It is also available through the world-wide-web at this URL:
http://wiserobot.com/mage_extension_license.pdf
=================================================================
MAGENTO COMMUNITY EDITION USAGE NOTICE
=================================================================
This package is designed for the Magento COMMUNITY edition
This extension may not work on any other Magento edition except Magento COMMUNITY edition. WiseRobot does not provide extension support in case of incorrect edition usage.
=================================================================
Copyright (c) 2013 Wiserobot Co.,LTD (http://www.wiserobot.com)
License http://wiserobot.com/mage_extension_license.pdf
*/
class Wiserobot_LisTrakNewsletter_Model_Observer extends Mage_Core_Block_Abstract {

	public function runJobs(){
		//$storeId=Mage::app()->getStore()->getStoreId();
		
		Mage::log('da chay den observer -> runJobs');


		$mainSubscriber=Mage::getStoreConfig('listraknewsletter/modules/main_subscriber');
		$storeId=1;
        $api=Mage::getModel('listraknewsletter/api');
		$contactlist=$api->ReportSubscribedContacts($storeId);

	    $subscribers=array();
	    if(isset($contactlist->ReportSubscribedContactsResult)){
	        $subscribers=$contactlist->ReportSubscribedContactsResult->WSContactSubscriptionInfo;
	    }

		
		$lisTrakAttributes=json_decode(Mage::getStoreConfig('listraknewsletter/api/attributes',Mage::app()->getStore()),true);
		if(!$lisTrakAttributes){
			$lisTrakAttributes=$api->getProfileHeaderCollection($storeId);
			Mage::getModel('core/config')->saveConfig('listraknewsletter/api/attributes',json_encode($lisTrakAttributes),'default',$storeId);
		}

		$index=array();
		foreach ($lisTrakAttributes as $attLabel=>$attId) {
			$index[$attId]=$attLabel;
		}
		$lsSubscribers=array();
		$lsEmails=array();
		$i=0;
	    
		foreach ($subscribers as $subscriber) {
			if($i++>10)break;
	        	$contact=$api->getContact($subscriber->EmailAddress,$storeId);
	        	$data=array();
	        	foreach ($contact->WSContact->ContactProfileAttribute as $lsAttribute) {
	        		$data[$index[$lsAttribute->AttributeID]]=$lsAttribute->Value;
	        	}
	        	$lsSubscribers[]=$data;
	        	$lsEmails[]=$subscriber->EmailAddress;
	        	//Mage::log($data,null, 'listrak.log');
		}
	    
		$cAllMageSubscribers=Mage::getModel('listraknewsletter/subscriber')->getCollection()->addFieldToFilter('store_id',$storeId);
		$allMageSubscribers=array();
		foreach ($cAllMageSubscribers as $mageSubscriber) {
			$allMageSubscribers[]=$mageSubscriber->getEmail();
		}

		$insertToLsSubscribers=array_diff($allMageSubscribers, $lsEmails);
		$insertToMageSubscribers=array_diff($lsEmails, $allMageSubscribers);
		$updateSubscribers=array_intersect($lsEmails, $allMageSubscribers);

		//Begin Add subscriber to Listrak
		foreach ($insertToLsSubscribers as $email) {
			$this->uploadSubscriber($email,1);
		}
		//End Add subscriber to Listrak
	

		//Begin Add subscriber to Magento

		foreach ($insertToMageSubscribers as $email) {
			$this->downloadSubscriber($email,$storeId);
		}
		//End Add subscriber to Magento


		//Begin Update subscriber

		foreach ($updateSubscribers as $email) {
			
			if($mainSubscriber=='magento'){
				$this->uploadSubscriber($email,$storeId);
			}
			elseif($mainSubscriber=='listrak'){
				$this->downloadSubscriber($email,$storeId);
			}
			
		}
		//End Update subscriber
	}



    //vtuong can sua o day
	protected function downloadSubscriber($email,$storeId){
		$api=Mage::getModel('listraknewsletter/api');
		$contact=$api->getContact($email,$storeId);
    	//Begin save subscriber
    	$lsSubscriber=Mage::getModel('listraknewsletter/subscriber');
    	$subscriberId=Mage::getResourceModel('listraknewsletter/subscriber')->getIdByEmail($email,$storeId);
    	
    	$lisTrakAttributes=json_decode(Mage::getStoreConfig('listraknewsletter/api/attributes',Mage::app()->getStore()),true);
    	$index=array();
		foreach ($lisTrakAttributes as $attLabel=>$attId) {
			$index[$attId]=$attLabel;
		}

    	if($subscriberId){
    		$lsSubscriber=$lsSubscriber->load($subscriberId);
    	}
    	$data=array();

    	foreach ($contact->WSContact->ContactProfileAttribute as $lsAttribute) {
    		$data[$index[$lsAttribute->AttributeID]]=$lsAttribute->Value;
    	}
    	//Begin set subscriber info
    	if($data['First Name'])$lsSubscriber->setFirstname($data['First Name']);
    	if($data['Last Name'])$lsSubscriber->setLastname($data['Last Name']);
    	if($data['Phone Number'])$lsSubscriber->setPhone($data['Phone Number']);
    	if($data['Region'])$lsSubscriber->setRegion($data['Region']);
    	if($data['Timezone'])$lsSubscriber->setTimeZone($data['Timezone']);
    	$lsSubscriber->setStoreId($storeId);
        $lsSubscriber->setEmail($email);
        $lsSubscriber->setStatus(1);


    	//End set subscriber info
    	$lsSubscriber->save();
	}

	protected function uploadSubscriber($email,$storeId){
		$api=Mage::getModel('listraknewsletter/api');
		$subscriberId=Mage::getResourceModel('listraknewsletter/subscriber')->getIdByEmail($email,$storeId);

        //echo "Subscriber ID: ".$subscriberId;
        	if($subscriberId){
        		$subscriber=Mage::getModel('listraknewsletter/subscriber')->load($subscriberId);
        	}


		if($subscriber->getStatus()==1){
    		//$res=$api->subscribeEmail($email,$storeId);

            $listrackdata=array(
                    'First Name'=>$subscriber->getFirstname(),
                    'Last Name'=>$subscriber->getLastname(),
                    'Phone'=>$subscriber->getPhone(),
                    'Country'=>$subscriber->getCountry(),
                    'Region'=>$subscriber->getRegion(),
                );

            $lisTrak=Mage::getModel('listraknewsletter/api')->setContact($email,$listrackdata,$storeId);
    	}
    	elseif ($subscriber->getStatus()==2) {
            $listrackdata=array(
                    'First Name'=>$subscriber->getFirstname(),
                    'Last Name'=>$subscriber->getLastname(),
                    'Phone'=>$subscriber->getPhone(),
                    'Country'=>$subscriber->getCountry(),
                    'Region'=>$subscriber->getRegion(),
                );

            $lisTrak=Mage::getModel('listraknewsletter/api')->setContact($email,$listrackdata,$storeId);

			$res=$api->unsubscribeEmail($email,$storeId);
		}
	}


	public function adminhtmlSaveOrder($observer){
		try {
			$order = $observer->getEvent()->getOrder();
			$quote = $observer->getEvent()->getQuote();
			$api=Mage::getModel('listraknewsletter/api');
			$email=Mage::app()->getRequest()->getParam('email');
			$orderParams=Mage::app()->getRequest()->getParam('order');
			$issubscribe=$orderParams['account']['subscribe'];
			$storeId=$order->getStoreId();
			if($issubscribe){
				$res=$api->subscribeEmail($email,$storeId);
			}
		} catch (Exception $e) {
			
		}
		
		return;
	}

	public function subscribeCustomer($observer)
	{
		try {
			$customer = $observer->getEvent()->getCustomer();
			if (($customer instanceof Mage_Customer_Model_Customer))
			{
				$subscriber=Mage::getModel('newsletter/subscriber');
				$subscriber->loadByEmail($customer->getEmail());
				if ($customer->getIsSubscribed())
				{
					$api=Mage::getModel('listraknewsletter/api')->subscribeEmail($customer->getEmail(),$customer->getStoreId());
					$listrackdata=array(
						'First Name'=>$customer->getFirstname(),
						'Last Name'=>$customer->getLastname(),

					);
					$lisTrak=Mage::getModel('listraknewsletter/api')->setContact($customer->getEmail(),$listrackdata,$customer->getStoreId());
					$subscriberId=Mage::getResourceModel('listraknewsletter/subscriber')->getIdByEmail($customer->getEmail(),$customer->getStoreId());
					$subscriber=Mage::getModel('listraknewsletter/subscriber');
					if($subscriberId){
						$subscriber->load($subscriberId);
					}

					$billingAddress=$customer->getDefaultBilling();
					if($billingAddress ){
						if($billingAddress){
							$address = Mage::getModel('customer/address')->load($billingAddress);
						}
						
						$subscriber->setEmail($customer->getEmail());
						$subscriber->setCustomerId($customer->getId());
						$subscriber->setFirstname($customer->getFirstname());
						$subscriber->setLastname($customer->getLastname());
						if($address->getId()){
							$subscriber->setPhone($address->getTelephone());
							$subscriber->setRegion($address->getRegion());
							$subscriber->setCountry($address->getCountry());
						}
					}
					
					$subscriber->setStatus(1);
					$subscriber->setStoreId($customer->getStoreId());
					$subscriber->save();
				}
				else//if (($customer->getIsSubscribed()==false) && $subscriber->isSubscribed())
				{
					$api=Mage::getModel('listraknewsletter/api')->unsubscribeEmail($customer->getEmail());
					$subscriberId=Mage::getResourceModel('listraknewsletter/subscriber')->getIdByEmail($customer->getEmail(),$customer->getStoreId());
					$subscriber=Mage::getModel('listraknewsletter/subscriber');
					if($subscriberId){
						$subscriber->load($subscriberId);
						$subscriber->setStatus(2);
						$subscriber->save();
					}
				}
			}
		} catch (Exception $e) {
			
		}
		
		//exit;
		return $this;
	}

	public function setCustomerIsSubscribed($observer){
	    $quote = $observer->getEvent()->getQuote();
	    $customer = $quote->getCustomer();
	    $billaddress=$quote->getBillingAddress();
	    $issubscribe=$billaddress->getIsSubscribed();
	    if($issubscribe){
			$mailchimp=Mage::getModel('mailchimpnewsletter/mailchimpnewsletter')->subscribe($billaddress->getEmail(),array('FNAME'=>$billaddress->getFirstname(),'LNAME'=>$billaddress->getLastname()));
	    }else{
	        $mailchimp=Mage::getModel('mailchimpnewsletter/mailchimpnewsletter')->unsubscribeall($billaddress->getEmail());
	    }
		return $this;
	}
}
