<?php

class Wiserobot_LisTrakNewsletter_Model_Mysql4_Subscriber extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the listraknewsletter_id refers to the key field in your database table.
        $this->_init('listraknewsletter/subscriber', 'id');
    }

    public function getIdByEmail($email,$storeId)
    {

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('subscriber'), 'id')
            ->where('(email = :email) AND (store_id = :store_id)');

        $bind = array(':email' => (string)$email,':store_id' => (string)$storeId);

        return $adapter->fetchOne($select, $bind);
    }
    
}