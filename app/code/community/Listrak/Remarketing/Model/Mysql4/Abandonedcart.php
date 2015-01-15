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

class Listrak_Remarketing_Model_Mysql4_Abandonedcart
    extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_read;

    protected $_write;

    public function _construct()
    {
        $this->_init('listrak/session', 'id');
        $this->_read = $this->_getReadAdapter();
        $this->_write = $this->_getWriteAdapter();
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $object->setSession(Mage::getModel("listrak/session")->load($object->getId()));

        $this->loadCart($object);

        return parent::_afterLoad($object);
    }

    protected function loadCart(Mage_Core_Model_Abstract $object)
    {
        $products = array();

        foreach (Mage::getModel('sales/quote')->load($object->getQuoteId())->getAllVisibleItems() as $item) {
            $products[] = $this->_getCartProductEntity($item, $object->getStoreId());
        }

        $object->setProducts($products);
    }

    private function _getCartProductEntity($item, $storeId)
    {
        $productModel = Mage::getModel('catalog/product')->load($item->getProductId());
        $productType = $productModel->getTypeId();
        $children = $item->getChildren();
        $childrenCount = count($children);
        if (Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE == $productType && $childrenCount > 0) {
            $productModel = Mage::getModel('catalog/product')->load($children[0]->getProductId());
        }
        $product = Mage::helper('remarketing/product')->getProductEntity($productModel, $storeId, false, false, false);
        $product["qty"] = $item->getQty();
        $product["price"] = $item->getCalculationPrice();
        if (Mage_Catalog_Model_Product_Type::TYPE_BUNDLE == $productType && $childrenCount > 0) {
            $product['bundle_items'] = array();
            foreach ($children as $child) {
                $product['bundle_items'][] = $this->_getCartProductEntity($child, $storeId);
            }
        }
        return $product;
    }

}
