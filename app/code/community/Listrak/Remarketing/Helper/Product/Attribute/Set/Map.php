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

class Listrak_Remarketing_Helper_Product_Attribute_Set_Map
    extends Mage_Core_Helper_Abstract
{
    public function ensureDataConsistency()
    {
        $newSets = $this->newAttributeSetsCollection();

        // add the new product attribute sets to our table
        foreach ($newSets as $set) {
            Mage::getModel('listrak/product_attribute_set_map')
                ->setAttributeSetId($set->getAttributeSetId())
                ->save();
        }
    }

    public function newAttributeSetsCollection()
    {
        $r = Mage::getSingleton('core/resource');

        // all product attribute sets
        $allProductSets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId());

        // the sets already in the table
        $model = new Varien_Db_Select(
            Mage::getResourceModel('listrak/product_attribute_set_map')->getReadConnection()
        );
        $model
            ->from(array('current' => $r->getTableName('listrak/product_attribute_set_map')))
            ->where('main_table.attribute_set_id = current.attribute_set_id');

        // new product attribute sets
        $allProductSets->getSelect()
            ->where('NOT EXISTS (' . $model . ')');

        return $allProductSets;
    }
}

