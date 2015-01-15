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

class Listrak_Remarketing_Model_Apiextension_Api
    extends Mage_Api_Model_Resource_Abstract
{
    private $_attributesMap = array(
        'order' => array('order_id' => 'entity_id')
    );

    public function products($storeId = 1, $perPage = 50, $page = 1)
    {
        Mage::helper('remarketing')->requireCoreEnabled();

        try {
            Mage::app()->setCurrentStore($storeId);

            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addStoreFilter($storeId)
                ->addAttributeToSelect('*')
                ->setPageSize($perPage)
                ->setCurPage($page)
                ->load();

            Mage::getModel('cataloginventory/stock')->addItemsToProducts($collection);

            $results = array();

            foreach ($collection as $product) {
                $results[] = Mage::helper('remarketing/product')->getProductEntity($product, $storeId);
            }

            return $results;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function subscribers($storeId = 1, $startDate = null, $perPage = 50,
        $page = 1
    )
    {
        Mage::helper('remarketing')->requireCoreEnabled();

        try {
            if ($startDate === null || !strtotime($startDate)) {
                $this->_fault('incorrect_date');
            }

            $result = array();

            $collection = Mage::getModel("listrak/apiextension")
                ->getResource()
                ->subscribers($storeId, $startDate, $perPage, $page);

            foreach ($collection as $item) {
                $result[] = $item;
            }

            return $result;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function subscribersPurge($endDate = null)
    {
        try {
            if ($endDate === null || !strtotime($endDate)) {
                $this->_fault('incorrect_date');
            }

            $subscriberupdates = Mage::getModel("listrak/subscriberupdate")
                ->getCollection()
                ->addFieldToFilter('updated_at', array('lt' => $endDate));

            $count = 0;

            foreach ($subscriberupdates as $subscriberupdate) {
                $subscriberupdate->delete();
                $count++;
            }

            return $count;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function customers($storeId = 1, $websiteId = 1, $perPage = 50, $page = 1)
    {
        Mage::helper('remarketing')->requireCoreEnabled();

        try {
            Mage::app()->setCurrentStore($storeId);

            $collection = Mage::getModel('customer/customer')->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addAttributeToSelect('*')
                ->setPageSize($perPage)
                ->setCurPage($page);

            $results = array();

            foreach ($collection as $customer) {
                $results[] = $this->_getCustomerArray($customer);
            }

            return $results;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    private function _getCustomerArray($customer)
    {
        $fields = array('entity_id' => '', 'firstname' => '', 'lastname' => '',
            'email' => '', 'website_id' => '', 'store_id' => '', 'group_id' => '',
            'gender_name' => '', 'dob' => '', 'group_name' => '');
        Mage::helper('remarketing')->setGroupNameAndGenderNameForCustomer($customer);
        return array_intersect_key($customer->toArray(), $fields);
    }

    public function orderStatus($storeId = 1, $startDate = null, $endDate = null,
        $perPage = 50, $page = 1, $filters = null
    )
    {
        Mage::helper('remarketing')->requireCoreEnabled();

        try {
            $collection = Mage::getModel("sales/order")->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addAttributeToSelect('increment_id')
                ->addAttributeToSelect('updated_at')
                ->addAttributeToSelect('status')
                ->addFieldToFilter('updated_at', array('from' => $startDate, 'to' => $endDate))
                ->addFieldToFilter('status', array('neq' => 'pending'))
                ->setPageSize($perPage)->setCurPage($page)
                ->setOrder('updated_at', 'ASC');

            if (is_array($filters)) {
                try {
                    foreach ($filters as $field => $value) {
                        if (isset($this->_attributesMap['order'][$field])) {
                            $field = $this->_attributesMap['order'][$field];
                        }

                        $collection->addFieldToFilter($field, $value);
                    }
                } catch (Mage_Core_Exception $e) {
                    $this->_fault('filters_invalid', $e->getMessage());
                }
            }

            $results = array();

            foreach ($collection as $collectionItem) {
                $results[] = $collectionItem;
            }

            return $results;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    public function orders($storeId = 1, $startDate = null, $endDate = null,
        $perPage = 50, $page = 1
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

            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('created_at', array('from' => $startDate, 'to' => $endDate))
                ->addFieldToFilter('store_id', $storeId)
                ->setPageSize($perPage)->setCurPage($page)
                ->setOrder('created_at', 'ASC');

            $results = array();

            foreach ($orders as $order) {
                $result = array();
                $result['info']['entity_id'] = $order->getEntityId();
                $result['info']['order_id'] = $order->getIncrementId();
                $result['info']['status'] = $order->getStatus();;
                $result['info']['customer_firstname'] = $order->getCustomerFirstname();
                $result['info']['customer_lastname'] = $order->getCustomerLastname();
                $result['info']['customer_email'] = $order->getCustomerEmail();
                $result['info']['subtotal'] = $order->getSubtotal();
                $result['info']['tax_amount'] = $order->getTaxAmount();
                $result['info']['shipping_amount'] = $order->getShippingAmount();
                $result['info']['grand_total'] = $order->getGrandTotal();
                $result['info']['billing_firstname'] = $order->getBillingFirstname();
                $result['info']['created_at'] = $order->getCreatedAt();
                $result['info']['updated_at'] = $order->getUpdatedAt();

                $shipping = $order->getShippingAddress();
                if ($shipping) {
                    $result['shipping_address']['firstname'] = $shipping->getFirstname();
                    $result['shipping_address']['lastname'] = $shipping->getLastname();
                    $result['shipping_address']['company'] = $shipping->getCompany();
                    $result['shipping_address']['street'] = implode(', ', $shipping->getStreet());
                    $result['shipping_address']['city'] = $shipping->getCity();
                    $result['shipping_address']['region'] = $shipping->getRegion();
                    $result['shipping_address']['postcode'] = $shipping->getPostcode();
                    $result['shipping_address']['country'] = $shipping->getCountry();
                }

                $billing = $order->getbillingAddress();
                if ($billing) {
                    $result['billing_address']['firstname'] = $billing->getFirstname();
                    $result['billing_address']['lastname'] = $billing->getLastname();
                    $result['billing_address']['company'] = $billing->getCompany();
                    $result['billing_address']['street'] = implode(', ', $billing->getStreet());
                    $result['billing_address']['city'] = $billing->getCity();
                    $result['billing_address']['region'] = $billing->getRegion();
                    $result['billing_address']['postcode'] = $billing->getPostcode();
                    $result['billing_address']['country'] = $billing->getCountry();
                }

                $result['session'] = Mage::getModel("listrak/session")->load($order->getQuoteId(), 'quote_id');

                $result['product'] = array();
                foreach ($order->getAllItems() as $item) {
                    if ($item->getParentItem()) {
                        continue;
                    }
                    $result['product'][] = $this->_getOrderItemProductEntity($item, $storeId);
                }
                if ($order->getCustomerId()) {
                    $customer = Mage::getModel("customer/customer")->load($order->getCustomerId());
                    if ($customer) {
                        $result['customer'] = $this->_getCustomerArray($customer);
                    }
                }

                $results[] = $result;
            }

            return $results;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }

    private function _getOrderItemProductEntity($item, $storeId)
    {
        $productModel = Mage::getModel('catalog/product')->load($item->getProductId());
        $productType = $productModel->getTypeId();
        $childrenItems = $item->getChildrenItems();
        $childrenItemsCount = count($childrenItems);
        if (Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE == $productType && $childrenItemsCount > 0) {
            $productModel = Mage::getModel('catalog/product')->load($childrenItems[0]->getProductId());
        }
        $product = array();
        if ($productModel != null) {
            $product['sku'] = $productModel->getSku();
            $product['name'] = $productModel->getName();
            $product['product_price'] = $productModel->getPrice();
        } else {
            $product['sku'] = $item->getProductOptionByCode('simple_sku')
                ? $item->getProductOptionByCode('simple_sku') : $item->getSku();
            $product['name'] = $item->getName();
        }
        $product['price'] = $item->getPrice();
        $product['qty_ordered'] = $item->getQtyOrdered();
        if (Mage_Catalog_Model_Product_Type::TYPE_BUNDLE == $productType && $childrenItemsCount > 0) {
            $product['bundle_items'] = array();
            foreach ($childrenItems as $childItem) {
                $product['bundle_items'][] = $this->_getOrderItemProductEntity($childItem, $storeId);
            }
        }
        return $product;
    }

    public function info()
    {
        try {
            $result = array();
            $result["magentoVersion"] = Mage::getVersion();
            $result["listrakSettings"] = array(
                "coreEnabled" => Mage::helper('remarketing')->coreEnabled() ? "true" : "false",
                "reviewsApiEnabled" => Mage::helper('remarketing')->reviewsEnabled() ? "true" : "false"
            );
            $result["ini"] = array();

            $subModel = Mage::getModel("newsletter/subscriber");
            $orderModel = Mage::getModel("sales/order");
            $productModel = Mage::getModel('catalog/product');

            $result["classes"] = get_class($subModel) . ',' . get_class($orderModel) .
                ',' . get_class($orderModel->getCollection()) . ',' .
                get_class($productModel) . ',' . get_class($productModel->getCollection());

            $ra = Mage::getSingleton('core/resource')->getConnection('core_read');
            $countQueryText = "select count(*) as c from " .
                Mage::getModel("listrak/session")->getResource()->getTable("listrak/session");
            $numSessions = $ra->fetchRow($countQueryText);
            $countQueryText = "select count(*) as c from " .
                Mage::getModel("listrak/subscriberupdate")->getResource()->getTable("listrak/subscriber_update");
            $numSubUpdates = $ra->fetchRow($countQueryText);
            $countQueryText = "select count(*) as c from " .
                Mage::getModel("listrak/click")->getResource()->getTable("listrak/click");
            $numClicks = $ra->fetchRow($countQueryText);

            $result["counts"] = $numSessions['c'] . ',' . $numSubUpdates['c'] . ',' . $numClicks['c'];

            $result["modules"] = array();
            $modules = (array)Mage::getConfig()->getNode('modules')->children();

            foreach ($modules as $key => $value) {
                $valueArray = $value->asCanonicalArray();
                $active = (isset($valueArray["version"])) ? $valueArray["version"] : '';
                $version = (isset($valueArray["active"])) ? $valueArray["active"] : '';
                $result["modules"][] = "name=$key, version=" . $version .", isActive=" . $active;
            }

            $ini = array("session.gc_maxlifetime", "session.cookie_lifetime",
                "session.gc_divisor", "session.gc_probability");

            foreach ($ini as $iniParam) {
                $result["ini"][] = "$iniParam=" . ini_get($iniParam);
            }

            return $result;
        } catch (Exception $e) {
            throw Mage::helper('remarketing')->generateAndLogException("Exception occurred in API call: " . $e->getMessage(), $e);
        }
    }
}
