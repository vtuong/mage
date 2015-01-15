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

class Listrak_Remarketing_Model_Abandonedcart
    extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('listrak/abandonedcart');
    }

    public function prepareForReport()
    {
        $session = $this->getSession();
        $customer = $session->getCustomer();
        $this->setSessionId($session->getSessionId());

        if ($customer && $customer->getId()) {
            $this->setCustomerName($customer->getFirstname() . ' ' . $customer->getLastname());
            $this->setEmail($customer->getEmail());
            $this->setIsCustomer('true');
        } else {
            $this->setCustomerName('');
            $this->setIsCustomer('false');

            if (is_array($session->getEmails()) && count($session->getEmails()) > 0) {
                foreach ($session->getEmails() as $email) {
                    $this->setEmail($this->getEmail() . $email["email"] . ', ');
                }

                $this->setEmail(trim($this->getEmail(), ", "));
            } else {

            }
        }

    }
}