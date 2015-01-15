<?php
/**
 * Created by PhpStorm.
 * User: tuong
 * Date: 18/10/2014
 * Time: 09:00
 */
class Wiserobot_LisTrakNewsletter_Model_System_Config_Backend_Updateblock extends Mage_Adminhtml_Model_System_Config_Backend_File
{
    /**
     * Getter for allowed extensions of uploaded files
     *
     * @return array
     */
    protected function _beforeSave(){
        // Mage::log("da chay den _beforeSave");
        $value = $this->getValue();
        if(is_array($value)){
            Mage::log('_beforeSave is_array(value)==true');
            $this->setValue(json_encode($value));
            Mage::log('value=');
            Mage::log($value);
        }
        return $this;
    }
}
