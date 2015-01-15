<?php

class Wiserobot_Promotionpopup_Model_Mysql4_Promotionpopup_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('promotionpopup/promotionpopup');
    }
}