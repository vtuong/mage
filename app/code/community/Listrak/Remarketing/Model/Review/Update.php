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

class Listrak_Remarketing_Model_Review_Update
    extends Mage_Core_Model_Abstract
{
    const ACTIVITYTYPE_UPDATE = 1;
    const ACTIVITYTYPE_DELETE = 2;

    public function _construct()
    {
        parent::_construct();
        $this->_init('listrak/review_update');
    }

    public function loadByReviewId($reviewId)
    {
        return $this->getCollection()
            ->addFilter('review_id', $reviewId)
            ->getFirstItem();
    }

    public function getReviewUpdateCollection()
    {
        return $this->getCollection()->getReviewUpdateTime();
    }

    public function getRatingSummaryUpdateCollection()
    {
        return $this->getCollection()->getRatingSummaryUpdateTime();
    }

    public function markUpdated($reviewId, $entityId, $entityPkValue)
    {
        $this->mark($reviewId, $entityId, $entityPkValue, self::ACTIVITYTYPE_UPDATE);
    }

    public function markDeleted($reviewId, $entityId, $entityPkValue)
    {
        $this->mark($reviewId, $entityId, $entityPkValue, self::ACTIVITYTYPE_DELETE);
    }

    protected function mark($reviewId, $entityId, $entityPkValue, $activityType)
    {
        $this->setReviewId($reviewId);
        $this->setEntityId($entityId);
        $this->setEntityPkValue($entityPkValue);
        $this->setActivityTime(gmdate('Y-m-d H:i:s'));
        $this->setActivity($activityType);
        $this->save();
    }
}

