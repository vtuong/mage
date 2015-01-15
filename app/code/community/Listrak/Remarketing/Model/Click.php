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

class Listrak_Remarketing_Model_Click
    extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('listrak/click');
    }

    public function checkForClick()
    {
        foreach (Mage::app()->getRequest()->getParams() as $key => $value) {
            if (stripos($key, 'trk_') !== false) {
                $this->_recordClick();
                break;
            }
        }
    }

    private function _recordClick()
    {
        $this->setTokenUid(Mage::helper('remarketing')->genUuid());
        $this->setClickDate(gmdate('Y-m-d H:i:s'));
        $session = Mage::getSingleton('listrak/session');
        $session->init();
        $this->setSessionId($session->getId());
        $this->setQuerystring(http_build_query(Mage::app()->getRequest()->getParams()));
        $this->save();

        Mage::getModel('core/cookie')->set('ltktrk', $this->getTokenUid(), true, null, null, null, true);
    }
}