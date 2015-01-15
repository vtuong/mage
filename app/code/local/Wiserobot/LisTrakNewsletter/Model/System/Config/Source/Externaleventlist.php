<?php
class Wiserobot_LisTrakNewsletter_Model_System_Config_Source_Externaleventlist {

    public function toOptionArray(){        
       	$res=Mage::getModel('listraknewsletter/api')->conductorGetExternalEvents();
		$list=array();
		$externalEvent = array();
		if(is_object($res) && isset($res->WSConductorExternalEvent)) $list=$res->WSConductorExternalEvent;    
	    if(count($list)>0){
	        foreach ($list as $l) {
	       		if($l->Status=="Active") 
	       			$externalEvent[]=array('value' => $l->EventID, 'label' => $l->EventName);
	        }
	    }
        return $externalEvent;
    }
}