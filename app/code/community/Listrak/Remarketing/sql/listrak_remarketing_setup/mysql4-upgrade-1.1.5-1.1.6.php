<?php
/**
 * Listrak Remarketing Magento Extension Ver. 1.1.6
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

try {
    Mage::getModel("listrak/log")->addMessage("1.1.5-1.1.6 upgrade");
} catch (Exception $e) {
}

$installer->endSetup();

