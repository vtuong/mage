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

class Listrak_Remarketing_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    private $_customerGroups;
    private $_categoryRootIdForStores = array();

    public function setGroupNameAndGenderNameForCustomer($customer)
    {
        if ($this->_customerGroups == null) {
            $this->_customerGroups = array();
            foreach (Mage::getModel('customer/group')->getCollection() as $group) {
                $this->_customerGroups[$group['customer_group_id']] = $group['customer_group_code'];
            }
        }
        if (array_key_exists($customer->getGroupId(), $this->_customerGroups)) {
            $customer->setGroupName($this->_customerGroups[$customer->getGroupId()]);
        }
        $customer->setGenderName(
            Mage::getResourceSingleton('customer/customer')
                ->getAttribute('gender')
                ->getSource()
                ->getOptionText($customer->getGender())
        );
    }

    public function genUuid()
    {
        // 32 bits for "time_low"
        // 16 bits for "time_mid"
        // 16 bits for "time_hi_and_version", four most significant bits holds version number 4
        // 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low",
        //      two most significant bits holds zero and one for variant DCE1.1
        // 48 bits for "node"
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    public function generateAndLogException($exceptionText, $sourceException)
    {
        $ex = new Exception("{UID: " . $this->genUuid() . "} " . $exceptionText, 0, $sourceException);
        Mage::getModel("listrak/log")->addException($ex);
        return $ex;
    }

    public function checkSetupStatus()
    {
        return Mage::getStoreConfig('remarketing/config/account_created');
    }

    public function displayAttributeSetNotification()
    {
        return (Mage::helper('remarketing/product_attribute_set_map')->newAttributeSetsCollection()->count() > 0);
    }

    public function coreEnabled()
    {
        return Mage::getStoreConfig('remarketing/modules/core');
    }

    public function reviewsEnabled()
    {
        return Mage::getStoreConfig('remarketing/modules/reviews');
    }

    public function requireCoreEnabled()
    {
        if (!$this->coreEnabled()) {
            throw new Exception('Listrak core functionality has been turned off in the System Configuration.');
        }
    }

    public function requireReviewsEnabled()
    {
        if (!$this->reviewsEnabled()) {
            throw new Exception('Listrak reviews API has been turned off in the System Configuration.');
        }
    }

    public function categoriesSource()
    {
        return Mage::getStoreConfig('remarketing/productcategories/categories_source');
    }

    public function getCategoryRootIdForStore($storeId)
    {
        if (!array_key_exists($storeId, $this->_categoryRootIdForStores)) {
            $this->_categoryRootIdForStores[$storeId] = Mage::getModel('core/store_group')
                ->load(
                    Mage::getModel('core/store')
                        ->load($storeId)
                        ->getGroupId()
                )
                ->getRootCategoryId();
        }
        return $this->_categoryRootIdForStores[$storeId];
    }
}
