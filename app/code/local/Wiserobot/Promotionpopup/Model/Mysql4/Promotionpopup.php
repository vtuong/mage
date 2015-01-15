<?php

class Wiserobot_Promotionpopup_Model_Mysql4_Promotionpopup extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the promotionpopup_id refers to the key field in your database table.
        $this->_init('promotionpopup/promotionpopup', 'promotionpopup_id');
    }
}