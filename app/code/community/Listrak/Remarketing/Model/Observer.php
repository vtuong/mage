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

class Listrak_Remarketing_Model_Observer
{

    public function sessionInit($observer)
    {
        if (Mage::helper('remarketing')->coreEnabled()) {
            try {
                $session = Mage::getSingleton('listrak/session');
                $session->init(true);

                $click = Mage::getModel('listrak/click');
                $click->checkForClick();
            } catch (Exception $ex) {
                Mage::getModel("listrak/log")->addException($ex);
            }
        }

        return $this;
    }

    public function orderPlaced($observer)
    {
        if (Mage::helper('remarketing')->coreEnabled()) {
            try {
                $cs = Mage::getSingleton('core/session');
                $cs->setIsListrakOrderMade(true);
                $session = Mage::getSingleton('listrak/session');
                $session->init();
            } catch (Exception $ex) {
                Mage::getModel("listrak/log")->addException($ex);
            }
        }

        return $this;
    }

    public function subscriberSaved($observer)
    {
        if (Mage::helper('remarketing')->coreEnabled()) {
            try {
                $s = $observer->getSubscriber();
                $su = Mage::getModel("listrak/subscriberupdate")->load($s->getSubscriberId(), 'subscriber_id');

                if (!$su->getData()) {
                    $su->setSubscriberId($s->getSubscriberId());
                }

                $su->setUpdatedAt(gmdate('Y-m-d H:i:s'));
                $su->save();
            } catch (Exception $ex) {
                Mage::getModel("listrak/log")->addException($ex);
            }
        }

        return $this;
    }

    public function reviewUpdated($observer)
    {
        if (Mage::helper('remarketing')->reviewsEnabled()) {
            try {
                $review = $observer->getObject();

                Mage::getModel('listrak/review_update')
                    ->markUpdated($review->getReviewId(), $review->getEntityId(), $review->getEntityPkValue());
            } catch (Exception $ex) {
                Mage::getModel("listrak/log")->addException($ex);
            }
        }

        return $this;
    }

    public function reviewDeleted($observer)
    {
        if (Mage::helper('remarketing')->reviewsEnabled()) {
            try {
                $review = $observer->getObject();

                Mage::getModel('listrak/review_update')
                    ->markDeleted($review->getReviewId(), $review->getEntityId(), $review->getEntityPkValue());
            } catch (Exception $ex) {
                Mage::getModel('listrak/log')->addException($ex);
            }
        }

        return $this;
    }
}
