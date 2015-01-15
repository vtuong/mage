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

class Listrak_Remarketing_Model_Abandonedcart_Api
    extends Mage_Api_Model_Resource_Abstract
{

    public function items($storeId = 1, $startDate = null, $endDate = null,
        $perPage = 50,
        $page = 1
    )
    {
        Mage::helper('remarketing')->requireCoreEnabled();

        try {
            Mage::app()->setCurrentStore($storeId);

            if ($startDate === null || !strtotime($startDate)) {
                $this->_fault('incorrect_date');
            }

            if ($endDate === null || !strtotime($endDate)) {
                $this->_fault('incorrect_date');
            }

            $storeIdArray = explode(',', $storeId);

            $collection = Mage::getModel('listrak/abandonedcart')
                ->getCollection()
                ->addFieldToFilter('main_table.updated_at', array('from' => $startDate, 'to' => $endDate))
                ->addClearCartTrimFilter($startDate)
                ->setPageSize($perPage)->setCurPage($page)
                ->addStoreFilter($storeIdArray)
                ->setOrder('updated_at', 'ASC')
                ->distinct(true);

            $result = array();

            foreach ($collection as $item) {
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

            $sessions = Mage::getModel("listrak/session")
                ->getCollection()
                ->addFieldToFilter('updated_at', array('lt' => $endDate));

            $count = 0;

            foreach ($sessions as $session) {
                $session->delete();
                $count++;
            }

            return $count;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }
}
