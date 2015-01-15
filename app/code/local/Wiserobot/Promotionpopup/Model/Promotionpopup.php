<?php

class Wiserobot_Promotionpopup_Model_Promotionpopup extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('promotionpopup/promotionpopup');
    }
}