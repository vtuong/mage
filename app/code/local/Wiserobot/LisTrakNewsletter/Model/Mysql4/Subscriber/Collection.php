<?php

class Wiserobot_LisTrakNewsletter_Model_Mysql4_Subscriber_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('listraknewsletter/subscriber');
    }
}