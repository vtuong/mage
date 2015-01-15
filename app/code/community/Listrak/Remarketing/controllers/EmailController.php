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

class Listrak_Remarketing_EmailController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        try {
            $email = $this->getRequest()->getParam('email');
            if (Zend_Validate::is($email, 'EmailAddress')) {
                $emailcaptureId = $this->getRequest()->getParam('cid');
                $session = Mage::getSingleton('listrak/session')->init();
                $emailcapture = Mage::getModel('listrak/emailcapture')->load($emailcaptureId);

                if ($emailcapture->getId()) {
                    $session->getResource()->insertEmail($session, $email, $emailcaptureId);
                    $result = array('status' => true);
                } else {
                    $result = array('status' => false);
                }
                header('Content-type: application/json');
                echo json_encode($result);
            }
        } catch (Exception $e) {
            Mage::getModel("listrak/log")->addException($e);
        }
    }

    public function fsidAction()
    {
        $email = $this->getRequest()->getParam('email');

        if (!Zend_Validate::is($email, 'EmailAddress')) {
            echo "invalid";
        } else {
            $emailcaptureId = $this->getRequest()->getParam('cid');
            $session = Mage::getModel('listrak/session');
            $session->setSessionId($this->getRequest()->getParam('ltksid'));
            $session->getResource()->loadBySessionId($session);
            $session->getResource()->insertEmail($session, $email, $emailcaptureId);

            echo json_encode(array('status' => true));
        }
    }
}
