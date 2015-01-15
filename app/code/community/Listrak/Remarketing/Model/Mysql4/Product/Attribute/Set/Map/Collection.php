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

class Listrak_Remarketing_Model_Mysql4_Product_Attribute_Set_Map_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    // so we don't go getting it all the time
    private $_productTypeId;

    protected function _construct()
    {
        $this->_init('listrak/product_attribute_set_map');
        $this->_productTypeId = Mage::getModel('catalog/product')->getResource()->getTypeId();
    }

    public function addMapIdFilter($ids)
    {
        $this->getSelect()->where('map_id IN (' . $ids . ')');

        return $this;
    }

    public function addAttributeSetFilter($id)
    {
        $this->getSelect()->where('attribute_set_id = ' . $id);

        return $this;
    }

    public function addAttributeSetName()
    {
        $resource = Mage::getSingleton('core/resource');

        // join in with the current settings to fetch attribute codes
        $this->getSelect()
            ->join(
                array('attribute_set' => $resource->getTableName('eav/attribute_set')),
                'main_table.attribute_set_id = attribute_set.attribute_set_id',
                array('attribute_set_name')
            );

        $this->getSelect()
            ->where('attribute_set.entity_type_id = ?', $this->_productTypeId);

        return $this;
    }

    public function addAttributeNames()
    {
        $r = Mage::getSingleton('core/resource');
        $attributeTable = $r->getTableName('eav/attribute');

        // add brand attribute name
        $this->getSelect()
            ->joinLeft(
                array('brand_attribute' => $attributeTable),
                'main_table.brand_attribute_code = brand_attribute.attribute_code',
                array('brand_attribute_name' => 'frontend_label')
            );

        // add category attribute name
        $this->getSelect()
            ->joinLeft(
                array('cat_attribute' => $attributeTable),
                'main_table.category_attribute_code = cat_attribute.attribute_code',
                array('category_attribute_name' => 'frontend_label')
            );

        // add subcategory attribute name
        $this->getSelect()
            ->joinLeft(
                array('subcat_attribute' => $attributeTable),
                'main_table.subcategory_attribute_code = subcat_attribute.attribute_code',
                array('subcategory_attribute_name' => 'frontend_label')
            );

        $brandFilter = 'brand_attribute.entity_type_id = ' . $this->_productTypeId
            . ' OR brand_attribute.entity_type_id IS NULL';
        $categoryFilter = 'cat_attribute.entity_type_id = ' . $this->_productTypeId
            . ' OR cat_attribute.entity_type_id IS NULL';
        $subcategoryFiler = 'subcat_attribute.entity_type_id = '
            . $this->_productTypeId . ' OR subcat_attribute.entity_type_id IS NULL';
        $this->getSelect()
            ->where($brandFilter)
            ->where($categoryFilter)
            ->where($subcategoryFiler);

        return $this;
    }

    public function orderByAttributeSetName()
    {
        $this->getSelect()->order('attribute_set_name ' . Varien_Db_Select::SQL_ASC);

        return $this;
    }
}

