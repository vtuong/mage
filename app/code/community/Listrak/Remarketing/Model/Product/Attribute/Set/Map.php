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

class Listrak_Remarketing_Model_Product_Attribute_Set_Map
    extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('listrak/product_attribute_set_map');

        //set the default values
        $this->setData('use_config_categories_source', 1);
    }

    public function finalCategoriesSource()
    {
        return $this->getUseConfigCategoriesSource()
            ? Mage::helper('remarketing')->categoriesSource() : $this->getCategoriesSource();
    }

    public function frontendCategoriesSource()
    {
        switch ($this->finalCategoriesSource()) {
        case 'default':
            return 'Magento Categories';
        case 'attributes' :
            return 'Product Attribute Mapping';
        default:
            return 'n/a';
        }
    }

    public function frontendBrandAttribute()
    {
        if ($this->getBrandAttributeCode() != null) {
            return $this->getBrandAttributeName() . ' (' . $this->getBrandAttributeCode() . ')';
        } else {
            return 'Not Set';
        }
    }

    public function frontendCategoryAttribute()
    {
        if ($this->finalCategoriesSource() == 'default') {
            return '';
        } else {
            if ($this->getCategoryAttributeCode() != null) {
                return $this->getCategoryAttributeName() . ' (' . $this->getCategoryAttributeCode() . ')';
            } else {
                return 'Not Set';
            }
        }
    }

    public function frontendSubcategoryAttribute()
    {
        if ($this->finalCategoriesSource() == 'default') {
            return '';
        } else {
            if ($this->getSubcategoryAttributeCode() != null) {
                return $this->getSubcategoryAttributeName() . ' (' . $this->getSubcategoryAttributeCode() . ')';
            } else {
                return 'Not Set';
            }
        }
    }

    public function save()
    {
        $this->setData('updated_at', gmdate('Y-m-d H:i:s'));
        parent::save();
    }
}

