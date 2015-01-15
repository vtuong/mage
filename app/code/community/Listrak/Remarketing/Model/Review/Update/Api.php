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

class Listrak_Remarketing_Model_Review_Update_Api
    extends Mage_Api_Model_Resource_Abstract
{
    private $_statuses = array();

    private $_ratings = array();

    protected function getStatuses()
    {
        if (sizeof($this->_statuses) == 0) {
            foreach (Mage::getModel('review/review')->getStatusCollection()->getItems() as $status) {
                $this->_statuses[$status->getStatusId()] = $status->getStatusCode();
            }
        }

        return $this->_statuses;
    }

    protected function getRatings()
    {
        if (sizeof($this->_ratings) == 0) {
            foreach (Mage::getModel('rating/rating')->getResourceCollection()->getItems() as $rating) {
                $this->_ratings[$rating->getRatingId()] = $rating->getRatingCode();
            }
        }

        return $this->_ratings;
    }

    public function reviewList($storeId, $chunkSize, $startReviewId)
    {
        Mage::helper('remarketing')->requireReviewsEnabled();

        try {
            $getStoreId = is_numeric($storeId) ? $storeId : 1;
            $getChunkSize = is_numeric($chunkSize) ? $chunkSize : 50;
            $fromReviewId = $startReviewId + 1;

            $collection = Mage::helper('remarketing/review_update')->getReviewListCollection($getStoreId);
            $collection
                ->getSelect()
                ->where("main_table.review_id >= ?", $fromReviewId)
                ->reset(Zend_Db_Select::ORDER)
                ->order('main_table.review_id ' . Varien_Db_Select::SQL_ASC)
                ->limit($getChunkSize);

            return $this->_reviewsFromCollection($getStoreId, $collection);
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function reviewUpdateList($storeId, $chunkSize, $startUpdateId)
    {
        Mage::helper('remarketing')->requireReviewsEnabled();

        try {
            $getStoreId = is_numeric($storeId) ? $storeId : 1;
            $getChunkSize = is_numeric($chunkSize) ? $chunkSize : 50;
            $fromUpdateId = $startUpdateId + 1;

            $collection = Mage::helper('remarketing/review_update')->getReviewListCollection($getStoreId);
            $collection
                ->getSelect()
                ->where("updatetime.update_id >= ?", $fromUpdateId)
                ->reset(Zend_Db_Select::ORDER)
                ->order('updatetime.update_id ' . Varien_Db_Select::SQL_ASC)
                ->limit($getChunkSize);

            return $this->_reviewsFromCollection($getStoreId, $collection);
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    private function _reviewsFromCollection($storeId, $reviewCollection)
    {
        $statuses = $this->getStatuses();
        $ratings = $this->getRatings();

        $reviews = array();

        foreach ($reviewCollection as $review) {
            $reviewId = $review->getReviewId();

            $reviewRatings = array();
            $votesCollection = Mage::getModel('rating/rating_option_vote')
                ->getResourceCollection()
                ->setReviewFilter($reviewId);

            foreach ($votesCollection as $vote) {
                $ratingId = $vote->getRatingId();

                array_push(
                    $reviewRatings, array(
                                      "rating_id" => $ratingId,
                                      "rating_code" => $ratings[$ratingId],
                                      "rating" => round($vote->getPercent() / 20, 4)
                                  )
                );
            }

            $overallRatingObj = Mage::getModel('rating/rating')
                ->getReviewSummary($reviewId, false);

            $overallRating = 0;
            foreach ($overallRatingObj as $ratingObj) {
                if ($ratingObj->getStoreId() == $storeId) {
                    if ($ratingObj->getCount()) {
                        $overallRating = $ratingObj->getSum() / $ratingObj->getCount();
                    }

                    break;
                }
            }

            array_push(
                $reviews, array(
                            "update_id" => $review->getUpdateId(),
                            "review_id" => $reviewId,
                            "product_id" => $review->getEntityPkValue(),
                            "title" => $review->getTitle(),
                            "text" => $review->getDetail(),
                            "overall_rating" => round($overallRating / 20, 4),
                            "created_at" => $review->getCreatedAt(),
                            "updated_at" => $review->getUpdatedAt(),
                            "reviewer_name" => $review->getNickname(),
                            "email" => $review->getEmail(),
                            "status_id" => $review->getStatusId(),
                            "status_code" => $statuses[$review->getStatusId()],
                            "ratings" => $reviewRatings
                        )
            );
        }

        return $reviews;
    }

    public function ratingSummaryList($storeId, $chunkSize, $startRatingSummaryId)
    {
        Mage::helper('remarketing')->requireReviewsEnabled();

        try {
            $getStoreId = is_numeric($storeId) ? $storeId : 1;
            $getChunkSize = is_numeric($chunkSize) ? $chunkSize : 50;
            $fromRatingSummaryId = $startRatingSummaryId + 1;

            $collection = Mage::helper('remarketing/review_update')->getRatingSummaryListCollection($getStoreId);
            $collection->getSelect()
                ->where('review_entity_summary.entity_type = ?', 1)
                ->where("review_entity_summary.primary_id >= ?", $fromRatingSummaryId)
                ->order('review_entity_summary.primary_id ' . Varien_Db_Select::SQL_ASC)
                ->limit($getChunkSize);

            return $this->_ratingSummariesFromCollection($getStoreId, $collection);
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function ratingSummaryUpdateList($storeId, $chunkSize, $startUpdateId)
    {
        Mage::helper('remarketing')->requireReviewsEnabled();

        try {
            $getStoreId = is_numeric($storeId) ? $storeId : 1;
            $getChunkSize = is_numeric($chunkSize) ? $chunkSize : 50;
            $fromUpdateId = $startUpdateId + 1;

            $collection = Mage::helper('remarketing/review_update')->getRatingSummaryListCollection($getStoreId);
            $collection->getSelect()
                ->where('review_entity_summary.entity_type = ?', 1)
                ->where("updatetime.update_id >= ?", $fromUpdateId)
                ->order('updatetime.update_id ' . Varien_Db_Select::SQL_ASC)
                ->limit($getChunkSize);

            return $this->_ratingSummariesFromCollection($getStoreId, $collection);
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    private function _ratingSummariesFromCollection($storeId, $collection)
    {
        $ratingSummaries = array();

        foreach ($collection as $ratingSummary) {
            $productId = $ratingSummary->getEntityPkValue();

            $ratingCollection = Mage::getModel('rating/rating')
                ->getResourceCollection()
                ->setStoreFilter($storeId)
                ->load()
                ->addEntitySummaryToItem($productId, $storeId);

            $ratings = array();
            foreach ($ratingCollection as $rating) {
                array_push(
                    $ratings, array(
                                "rating_id" => $rating->getRatingId(),
                                "rating_code" => $rating->getRatingCode(),
                                "rating" => round($rating->getSummary() / 20, 4)
                            )
                );
            }

            array_push(
                $ratingSummaries, array(
                                    "update_id" => $ratingSummary->getUpdateId(),
                                    "rating_summary_id" => $ratingSummary->getPrimaryId(),
                                    "product_id" => $productId,
                                    "updated_at" => $ratingSummary->getUpdatedAt(),
                                    "total_reviews" => $ratingSummary->getReviewsCount(),
                                    "rating" => round($ratingSummary->getRatingSummary() / 20, 4),
                                    "ratings" => $ratings
                                )
            );
        }

        return $ratingSummaries;

    }

    public function reviewDeleteList($chunkSize, $startDeleteId)
    {
        Mage::helper('remarketing')->requireReviewsEnabled();

        try {
            $mageResource = Mage::getSingleton('core/resource');
            $dbRead = $mageResource->getConnection('core_read');

            $getChunkSize = is_numeric($chunkSize) ? $chunkSize : 50;
            $fromDeleteId = $startDeleteId + 1;

            $collection = Mage::getModel('listrak/review_update')
                ->getCollection()
                ->productReviewsOnly()
                ->deletedRowsOnly();

            $allReviewIDs = $dbRead
                ->select()
                ->from(
                    array('review' => Mage::getSingleton('core/resource')->getTableName('review/review')),
                    'review.review_id'
                );

            $collection->getSelect()
                ->where('NOT EXISTS (' . $allReviewIDs->where('main_table.review_id = review.review_id') . ')')
                ->where("update_id >= ?", $fromDeleteId)
                ->limit($getChunkSize);

            $deletedReviews = array();
            foreach ($collection as $deletedReview) {
                array_push(
                    $deletedReviews, array(
                                       "delete_id" => $deletedReview->getUpdateId(),
                                       "review_id" => $deletedReview->getReviewId()
                                   )
                );
            }

            return $deletedReviews;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function reviewUpdatePurge($purgeBeforeDays)
    {
        try {
            $mageResource = Mage::getSingleton('core/resource');
            $dbWrite = $mageResource->getConnection('core_write');

            $doPurgeBeforeDays = is_numeric($purgeBeforeDays) ? $purgeBeforeDays : 30;
            $purgeBefore = $doPurgeBeforeDays > 0
                ? gmdate('Y-m-d H:i:s', strtotime("-{$doPurgeBeforeDays} days")) : gmdate('Y-m-d H:i:s');

            $rowsDeleted = $dbWrite->delete(
                $mageResource->getTableName('listrak/review_update'),
                array('activity_time < ?' => $purgeBefore)
            );

            return array(
                "count" => $rowsDeleted,
                "before" => $purgeBefore
            );
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }
}

