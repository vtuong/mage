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

class Listrak_Remarketing_CartController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        return $this;
    }

    public function reloadAction()
    {
        $checkout = Mage::getSingleton('checkout/session');
        $cust = Mage::getSingleton('customer/session');
        $ltksid = $this->getRequest()->getParam('ltksid');
        $ltksession = Mage::getModel("listrak/session");
        $ltksidcookie = Mage::getModel('core/cookie')->get('ltksid');
        $chkQuote = Mage::helper('checkout/cart')->getQuote();

        try {
            if (!$ltksid) {
                return $this->_redirectAfterReload();
            }

            if (!empty($ltksidcookie) && $ltksidcookie == $ltksid && $chkQuote && $chkQuote->getId()) {
                return $this->_redirectAfterReload();
            }

            $ltksession->setSessionId($ltksid);
            $ltksession->getResource()->loadBySessionId($ltksession);

            if (!$ltksession->getId() || !$ltksession->getQuoteId()) {
                return $this->_redirectAfterReload();
            }

            if ($cust && $cust->isLoggedIn()) {
                if ($cust->getId() === $ltksession->getCustomerId()) {
                    return $this->_redirectAfterReload();
                }
            }

            $quote = Mage::getModel('sales/quote')->load($ltksession->getQuoteId());

            if ($quote->getId() && $quote->getIsActive()) {
                $checkout->setQuoteId($ltksession->getQuoteId());
            }
        } catch (Exception $ex) {
            Mage::getModel("listrak/log")->addException($ex);
        }

        return $this->_redirectAfterReload();
    }

    private function _redirectAfterReload()
    {
        $qs = $this->getRequest()->getParams();
        unset($qs["redirectUrl"]);
        unset($qs["ltksid"]);

        $url = $this->getRequest()->getParam('redirectUrl');
        if (!$url) {
            $url = 'checkout/cart/';
        }

        return $this->_redirect(
            $url,
            array('_query' => $qs, '_secure' => Mage::app()->getStore()->isCurrentlySecure())
        );
    }
}
