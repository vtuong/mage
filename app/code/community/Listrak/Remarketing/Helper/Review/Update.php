<?php
/**
 * Listrak Remarketing Magento Extension Ver. 1.1.5
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

class Listrak_Remarketing_Helper_Review_Update
    extends Mage_Core_Helper_Abstract
{
    public function getReviewListCollection($storeId)
    {
        $collection = Mage::getModel('review/review')
            ->getCollection()
            ->addStoreFilter($storeId);

        $collection
            ->getSelect()
            ->joinLeft(
                array(
                    'updatetime' => Mage::getModel('listrak/review_update')->getReviewUpdateCollection()->getSelect()
                ),
                "main_table.review_id = updatetime.review_id",
                array("updatetime.update_id", "updatetime.updated_at")
            )
            ->joinLeft(
                array(
                    'employee' => Mage::getSingleton('core/resource')->getTableName('customer/entity')
                ),
                'detail.customer_id = employee.entity_id',
                array('employee.email')
            );

        return $collection;
    }

    public function getRatingSummaryListCollection($storeId)
    {
        $collection = Mage::getModel('review/review_summary')
            ->getCollection();

        $updatetimeSelect = Mage::getModel('listrak/review_update')->getRatingSummaryUpdateCollection()->getSelect();
        $joinOnClause = "review_entity_summary.primary_id = updatetime.rating_summary_id "
            . "AND review_entity_summary.store_id = updatetime.store_id";
        $collection->getSelect()
            ->joinLeft(
                array('updatetime' => $updatetimeSelect),
                $joinOnClause,
                array("updatetime.update_id", "updatetime.updated_at")
            )
            ->where('review_entity_summary.store_id = ?', $storeId);

        return $collection;
    }
}

