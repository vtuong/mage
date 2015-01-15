<?php
 
class Wiserobot_LisTrakNewsletter_Model_System_Config_Source_Listids
{
    public function toOptionArray()
    {
        $api=Mage::getModel('listraknewsletter/api');
        $list = $api->getContactListCollection();

        $return = array();
       if(is_array($list)){
           foreach ($list as $listID => $listName) {
               array_push($return, array('value' => $listID, 'label' => $listName ));
           }
       }
//        return array(
//            array("value" =>  "aaaaaaa","label" =>  "aaaaaaa"),
//            array("value" =>  "bbbbb","label" =>  "bbbbbbb"),
//            array("value" =>  "ccccccc","label" =>  "ccccccccc"),
//            array("value" =>  "ddddd","label" =>  "dddddđd"),
//        );
        return $return;
    }
}