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

class Listrak_Remarketing_ConfigController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        return $this;
    }

    public function registerAction()
    {
        $reg = Mage::getStoreConfig('remarketing/config/account_created');

        if (!$reg) {
            $gv = array();
            $gv['config']['fields']['account_created']['value'] = '1';
            Mage::getModel('adminhtml/config_data')
                ->setSection('remarketing')
                ->setWebsite(null)
                ->setStore(null)
                ->setGroups($gv)
                ->save();
            Mage::getConfig()->cleanCache();
        }
    }

    public function checkAction()
    {
        echo Mage::getStoreConfig('remarketing/config/account_created');
    }
}