<?php

/**
 * Created by PhpStorm.
 * User: tuong
 * Date: 13/10/2014
 * Time: 15:58
 */

class Wiserobot_LisTrakNewsletter_Model_Listracktypes extends Mage_Core_Model_Abstract {

    public function _construct()
    {
        parent::_construct();
        $this->_init('listraknewsletter/listracktypes');
    }

    public function toOptionArray(){
        $api=Mage::getModel('listraknewsletter/api');
        $list = $api->getProfileHeaderCollection();

        $return = array();
        if(is_array($list)){
            foreach ($list as $k => $v) {
                array_push($return, array('value' => $v, 'label' => $k ));
            }    
        }        
        // return $return;
        return array(
            array("value" =>  "First Name",     "label" =>  "First Name"),
            array("value" =>  "Last Name",      "label" =>  "Last Name"), 
            array("value" =>  "Gender",         "label" =>  "Gender"),
            array("value" =>  "Telephone",      "label" =>  "Telephone"),           
            array("value" =>  "Country",        "label" =>  "Country"),
            array("value" =>  "Region",         "label" =>  "Region"),
            array("value" =>  "Company",        "label" =>  "Company"),
            array("value" =>  "Address",        "label" =>  "Address"),
            array("value" =>  "City",           "label" =>  "City"),
            array("value" =>  "Zip",            "label" =>  "Zip/Postal Code"),
            array("value" =>  "Fax",            "label" =>  "Fax"),
            array("value" =>  "Birthday",       "label" =>  "Birthday"),
            array("value" =>  "VAT",            "label" =>  "VAT")
        );
    }
}
