<?php

class Wiserobot_LisTrakNewsletter_Model_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage {

    public function saveBilling($data, $customerAddressId) {

	    $customer = Mage::getSingleton('customer/session')->getCustomer();
    	$api=Mage::getModel('listraknewsletter/api');
    	$billingAddress = $this->getQuote()->getBillingAddress();
    	$store=Mage::app()->getStore();
		$attributes=json_decode(Mage::getStoreConfig('listraknewsletter/mapping_attributes/mapping_attributes'));//,$store);
    	
		if (isset($data['is_subscribed']) && !empty($data['is_subscribed']) && (Mage::getStoreConfig('listraknewsletter/general/enabled_signup_checkout'))) {
	    	$param=array();
	    	if ($customer->getId()) { 
	    		$api->subscribeEmail($customer->getEmail(),$store->getStoreId());
	        }else{ 
	        	$api->subscribeEmail($data['email'],$store->getStoreId()); 
	        }
            if($attributes && is_object($attributes) && $attributes->mage)
	        foreach ($attributes->mage as $k => $v) {
                $attrID=(int)$attributes->list[$k];
        		switch ($attributes->mage[$k]) {
        			case "First Name":
        				if ($customer->getId()) { 
        					$param[]=array('AttributeID'=>$attrID,'Value'=>$customer->getFirstname());
	        			}else{ 
	        				$param[]=array('AttributeID'=>$attrID,'Value'=>$data['firstname']);
	        			}        				
        				break;
        			case "Last Name":
        				if ($customer->getId()) { 
        					$param[]=array('AttributeID'=>$attrID,'Value'=>$customer->getLastname());
	        			}else{ 
	        				$param[]=array('AttributeID'=>$attrID,'Value'=>$data['lastname']);
	        			}        				
        				break;
        			case "Gender":
        				//$param[]=array('AttributeID'=>$attributes->list[$k],'Value'=>);
        				break;
        			case "Telephone":
        				$param[]=array('AttributeID'=>$attrID,'Value'=>$data['telephone']);
        				break;
        			case "Country":
        				$param[]=array('AttributeID'=>$attrID,'Value'=>$billingAddress->getCountry());
        				break;
        			case "Region":
        				$param[]=array('AttributeID'=>$attrID,'Value'=>$billingAddress->getRegion());
        				break;
                    case "City":
                        $param[]=array('AttributeID'=>$attrID,'Value'=>$data['city']);
                        break;
                    case "Address":
                        $param[]=array('AttributeID'=>$attrID,'Value'=>$data['street'][0]);
                        break;
                    case "Fax":
                        $param[]=array('AttributeID'=>$attrID,'Value'=>$data['fax']);
                        break;
                    case "Company":
                        $param[]=array('AttributeID'=>$attrID,'Value'=>$data['company']);
                        break;
                    case "Zip":
                        $param[]=array('AttributeID'=>$attrID,'Value'=>$data['postcode']);
                        break;
                    case "VAT":
                        if ($customer->getId()) { 
                            $param[]=array('AttributeID'=>$attrID,'Value'=>$customer->getTaxvat());
                        }
                        break;
                    case "Birthday":
                        if ($customer->getId()) { 
                            $param[]=array('AttributeID'=>$attrID,'Value'=>$customer->getDob());
                        }
                        break;
        		}
        	}
        	if ($customer->getId()) { 
        		$lisTrak=Mage::getModel('listraknewsletter/api')->setContact($customer->getEmail(), $param, $store->getStoreId());
	        }else{ 
	        	$lisTrak=Mage::getModel('listraknewsletter/api')->setContact($data['email'], $param, $store->getStoreId());
	        }
   //          echo "<br>data=";echo "<pre>";print_r($data);echo "</pre>";
			// echo "<br>param=";echo "<pre>";print_r($param);echo "</pre>";	      
	    }
        return parent::saveBilling($data, $customerAddressId);
    }
}
