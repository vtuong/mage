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
INSERT INTO {$this->getTable('listrak/emailcapture')} (`emailcapture_id` ,`page` ,`field_id`)
VALUES (NULL , '*', 'ltkmodal-email');
"
);

try {
    Mage::getModel("listrak/log")->addMessage("1.0.8-1.0.9 upgrade");
} catch (Exception $e) {
}

$installer->endSetup();