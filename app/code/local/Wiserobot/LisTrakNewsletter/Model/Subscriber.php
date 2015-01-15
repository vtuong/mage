<?php

class Wiserobot_LisTrakNewsletter_Model_Subscriber extends Mage_Core_Model_Abstract {
    
    public function _construct(){
        parent::_construct();
        $this->_init('listraknewsletter/subscriber');
    }

    public function setContactFromMappingInfo($data=array()){
    	$api=Mage::getModel('listraknewsletter/api');
    	// $billingAddress = $this->getQuote()->getBillingAddress();
    	$store=Mage::app()->getStore();
		$attributes=json_decode(Mage::getStoreConfig('listraknewsletter/mapping_attributes/mapping_attributes'));//,$store);
    	
		if(count($data)>0){
			$param=array();
	        foreach ($attributes->mage as $k => $v) {
	            $attrID=(int)$attributes->list[$k];
	    		switch ($attributes->mage[$k]) {
	    			case "First Name": 
	        			$param[]=array('AttributeID'=>$attrID,'Value'=>(string)$data['firstname']);    				
	    				break;
	    			case "Last Name": 
	        			$param[]=array('AttributeID'=>$attrID,'Value'=>(string)$data['lastname']);      				
	    				break;
	    			case "Gender":
	    				if(isset($data['gender']))
	    					$param[]=array('AttributeID'=>$attributes->list[$k],'Value'=>(string)$data['gender']);
	    				break;
	    			case "Telephone":
	    				// $param[]=array('AttributeID'=>$attrID,'Value'=>$data['telephone']);
	    				break;
	    			case "Country":
	    				// $param[]=array('AttributeID'=>$attrID,'Value'=>$billingAddress->getCountry());
	    				break;
	    			case "Region":
	    				// $param[]=array('AttributeID'=>$attrID,'Value'=>$billingAddress->getRegion());
	    				break;
	                case "City":
	                    // $param[]=array('AttributeID'=>$attrID,'Value'=>$data['city']);
	                    break;
	                case "Address":
	                    // $param[]=array('AttributeID'=>$attrID,'Value'=>$data['street'][0]);
	                    break;
	                case "Fax":
	                    // $param[]=array('AttributeID'=>$attrID,'Value'=>$data['fax']);
	                    break;
	                case "Company":
	                    // $param[]=array('AttributeID'=>$attrID,'Value'=>$data['company']);
	                    break;
	                case "Zip":
	                    // $param[]=array('AttributeID'=>$attrID,'Value'=>$data['postcode']);
	                    break;
	                case "VAT":
	                	if(isset($data['taxvat']))
	                    	$param[]=array('AttributeID'=>$attrID,'Value'=>(string)$data['taxvat']);
	                    break;
	                case "Birthday":
	                	if(isset($data['dob']))
	                    	$param[]=array('AttributeID'=>$attrID,'Value'=>(string)$data['dob']);
	                    break;
	    		}
	    	}
	        $res=Mage::getModel('listraknewsletter/api')->setContact($data['email'], $param, Mage::app()->getStore()->getStoreId());
			return $res;//$param;//
		}
    	    
    }

}