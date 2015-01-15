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

class Listrak_Remarketing_Model_Session extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('listrak/session');
    }

    public function init($createOnlyIfCartHasItems = false)
    {
        $ltksid = Mage::getModel('core/cookie')->get('ltksid');
        $piid = Mage::getModel('core/cookie')->get('personalmerchant');
        $custSession = Mage::getSingleton("customer/session");
        
        $cartHasItems = Mage::helper('checkout/cart')->getItemsCount() > 0;

        if (!empty($ltksid) && strlen($ltksid) > 37) {
            $ltkpk = intval(substr($ltksid, 37), 10);
            //$this->setSessionId($ltksid);
            $this->load($ltkpk);
            if ($this->getSessionId() !== substr($ltksid, 0, 36)) {
                $this->setData(array());
            }
            //$this->getResource()->loadBySessionId($this);
        }

        if (!empty($piid)) {
            $this->setPiId($piid);
        }

        if (!$this->getId()) {
            if ($createOnlyIfCartHasItems && !$cartHasItems) {
                return null;
            }
            $this->setCreatedAt(gmdate('Y-m-d H:i:s'));
            $this->setIsNew(true);
            $this->setHadItems($cartHasItems);
        } else {
            $this->setHadItems($this->getHadItems() || $cartHasItems);
        }

        if ($custSession->isLoggedIn()) {
            $this->setCustomerId($custSession->getId());
        }

        $quoteId = Mage::helper('checkout/cart')->getQuote()->getId();

        if ($quoteId) {
            $this->setQuoteId($quoteId);
        }

        $this->setStoreId(Mage::app()->getStore()->getStoreId());
        $this->setUpdatedAt(gmdate('Y-m-d H:i:s'));

        if (strlen($this->getIps()) > 0) {
            if (strpos($this->getIps(), $_SERVER["REMOTE_ADDR"]) === false) {
                $this->setIps($this->getIps() . "," . $_SERVER["REMOTE_ADDR"]);
            }
        } else {
            $this->setIps($_SERVER["REMOTE_ADDR"]);
        }

        if ($this->getIsNew() === true) {
            $saved = false;
            $tryCount = 0;
            while(!$saved && $tryCount < 2) {
                $tryCount++;

                try {
                    $this->setSessionId(Mage::helper('remarketing')->genUuid());
                    $this->save();
                    $saved = true;
                } catch(Exception $e) {
                    Mage::getModel('listrak/log')->addException(new Exception("{QuoteID: " . $this->getQuoteId() . ", SessionID: " . $this->getSessionId() . "} Exception when attempting to save session: " . $e->getMessage()));
                }
            }
            
            if (!$saved) {
                throw new Exception("{QuoteID: " . $this->getQuoteId() . "} Failed to save session. See previous exceptions.");
            }

            Mage::getModel('core/cookie')->set(
                'ltksid',
                $this->getSessionId() . '-' . $this->getId(),
                true, null, null, null, false
            );
        } else {
            $this->save();
        }

        $cs = Mage::getSingleton('core/session');

        if ($cs->getIsListrakOrderMade()) {
            Mage::getModel('core/cookie')->delete('ltksid');
            $cs->setIsListrakOrderMade(false);
        }

        return $this;
    }

    public function loadEmails()
    {
        $this->getResource()->loadEmails($this);
    }

    public function delete()
    {
        $this->getResource()->deleteEmails($this->getId());
        parent::delete();
    }
}
