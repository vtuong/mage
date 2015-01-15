<?php
/**
 * Listrak Remarketing Magento Extension Ver. 1.0.0
 *
 * PHP version 5
 *
 * @category  Listrak
 * @package   Listrak_Remarketing
 * @author    Listrak Magento Team <magento@listrak.com>
 * @copyright 2011 Listrak Inc
 * @license   http://s1.listrakbi.com/licenses/magento.txt License For Customer Use of Listrak Software
 * @link      http://www.listrak.com
 */

class Listrak_Remarketing_Model_Log extends Mage_Core_Model_Abstract
{
    const LOGTYPE_MESSAGE = 1;
    const LOGTYPE_EXCEPTION = 2;

    public function _construct()
    {
        parent::_construct();
        $this->_init('listrak/log');
    }

    public function addMessage($msg, $storeId = null)
    {
        if ($storeId == null) {
            $storeId = Mage::app()->getStore()->getStoreId();
        }
        $this->setMessage($msg);
        $this->setLogTypeId(self::LOGTYPE_MESSAGE);
        $this->setStoreId($storeId);
        $this->save();
    }

    public function addException($msg, $storeId = null)
    {
        if ($storeId == null) {
            $storeId = Mage::app()->getStore()->getStoreId();
        }
        $this->setMessage($msg);
        $this->setLogTypeId(self::LOGTYPE_EXCEPTION);
        $this->setStoreId($storeId);
        $this->save();
    }
}