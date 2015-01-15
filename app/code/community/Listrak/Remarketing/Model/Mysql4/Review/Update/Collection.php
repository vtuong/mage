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

class Listrak_Remarketing_Model_Mysql4_Review_Update_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('listrak/review_update');
    }

    public function productReviewsOnly()
    {
        $this->getSelect()
            ->where('entity_id = 1');

        return $this;
    }

    public function updatedRowsOnly()
    {
        $this->getSelect()
            ->where('activity = 1');

        return $this;
    }

    public function deletedRowsOnly()
    {
        $this->getSelect()
            ->where('activity = 2');

        return $this;
    }

    public function getReviewUpdateTime()
    {
        $this->productReviewsOnly()
            ->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns("review_id")
            ->columns(array("update_id" => "MAX(update_id)"))
            ->columns(array("updated_at" => "MAX(activity_time)"))
            ->group("main_table.review_id");

        return $this;
    }

    public function getRatingSummaryUpdateTime()
    {
        $this->productReviewsOnly()
            ->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(array("update_id" => "MAX(update_id)"))
            ->columns(array("updated_at" => "MAX(activity_time)"))
            ->join(
                array("agg" => Mage::getSingleton('core/resource')->getTableName('review/review_aggregate')),
                "main_table.entity_pk_value = agg.entity_pk_value AND main_table.entity_id = agg.entity_type",
                array("rating_summary_id" => "primary_id", "store_id" => "store_id")
            )
            ->group(array("agg.primary_id", "agg.store_id"));

        return $this;
    }
}

