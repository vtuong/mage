<?php
/**
 * Listrak Remarketing Magento Extension Ver. 1.1.8
 *
 * PHP version 5
 *
 * @category  Listrak
 * @package   Listrak_Remarketing
 * @author    Listrak Magento Team <magento@listrak.com>
 * @copyright 2013 Listrak Inc
 * @license   http://s1.listrakbi.com/licenses/magento.txt License For Customer Use of Listrak Software
 * @link      http://www.listrak.com
 */

$installer = $this;
$installer->startSetup();

$duplicateSessions = array();

// populate the sessions
$findDuplicatesCollection = Mage::getModel('listrak/session')->getCollection();
$findDuplicatesCollection->getSelect()
    ->group("session_id")
    ->having("COUNT(*) > 1");
foreach($findDuplicatesCollection as $session) {
    $duplicateSessions[] = $session->getSessionId();
}
    
// delete the duplicate sessions
$deleteDuplicatesCollection = Mage::getModel('listrak/session')->getCollection();
$deleteDuplicatesCollection->getSelect()
    ->where("session_id IN (?)", $duplicateSessions);
foreach($deleteDuplicatesCollection as $session) {
    $session->delete();
}

try {
    $installer->run("ALTER TABLE {$this->getTable('listrak/session')} DROP KEY `unique_session_id`;");
} catch(Exception $e) {
}

$installer->run("
ALTER TABLE {$this->getTable('listrak/session')}
  ADD UNIQUE KEY `unique_session_id` (`session_id`);
");

try {
    Mage::getModel("listrak/log")->addMessage("1.1.7-1.1.8 upgrade");
} catch (Exception $e) {
}

if (sizeof($duplicateSessions) > 0) {
    try {
        Mage::getModel('listrak/log')->addException("Removed duplicate sessions when updating the session table structure: " . implode(", ", $duplicateSessions));
    
        $client = new Varien_Http_Client("http://magento.listrakbi.com/Install.ashx");
        $client->setMethod(Varien_Http_Client::POST);
        $client->setParameterPost("Listrak Extension Version", "1.1.8");
        $client->setParameterPost("Client URL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $client->setParameterPost("Duplicate Sessions", implode(", ", $duplicateSessions));
        $client->request();
    } catch (Exception $e) {
    }
}

try {
    $client = new Varien_Http_Client("http://magento.listrakbi.com/Install.ashx");
    $client->setMethod(Varien_Http_Client::POST);
    $client->setParameterPost("Listrak Extension Version", "1.1.8");
    $client->setParameterPost("Magento Version", Mage::getVersion());
    $client->setParameterPost("Install URL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    $client->setParameterPost("IP Address", "$_SERVER[SERVER_ADDR]");
    $client->request();
} catch (Exception $e) {
}

$installer->endSetup();
