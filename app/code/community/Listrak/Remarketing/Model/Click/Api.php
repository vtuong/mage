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

class Listrak_Remarketing_Model_Click_Api
    extends Mage_Api_Model_Resource_Abstract
{

    public function items($storeId = 1, $startDate = null, $endDate = null,
        $perPage = 50, $page = 1
    )
    {
        Mage::helper('remarketing')->requireCoreEnabled();

        try {
            if ($startDate === null || !strtotime($startDate)) {
                $this->_fault('incorrect_date');
            }

            if ($endDate === null || !strtotime($endDate)) {
                $this->_fault('incorrect_date');
            }

            $clicks = Mage::getModel("listrak/click")->getCollection()
                ->addFieldToFilter('click_date', array('from' => $startDate, 'to' => $endDate))
                ->setPageSize($perPage)->setCurPage($page);

            $clicks->addStoreFilter($storeId);

            $result = array();

            foreach ($clicks as $item) {
                $result[] = $item;
            }

            return $result;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function purge($storeId = 1, $endDate = null)
    {
        try {
            if ($endDate === null || !strtotime($endDate)) {
                $this->_fault('incorrect_date');
            }

            $clicks = Mage::getModel("listrak/click")
                ->getCollection()
                ->addFieldToFilter('click_date', array('lt' => $endDate));

            $count = 0;

            foreach ($clicks as $click) {
                $click->delete();
                $count++;
            }

            return $count;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }
}
