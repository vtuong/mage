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

$installer = $this;
$installer->startSetup();

$installer->run(
    "
ALTER TABLE {$this->getTable('listrak/session')} ADD `ips` VARCHAR( 1000 ) CHARACTER SET ascii COLLATE ascii_general_ci
"
);

try {
    Mage::getModel("listrak/log")->addMessage("1.0.0-1.0.1 upgrade");
} catch (Exception $e) {
}

$installer->endSetup();