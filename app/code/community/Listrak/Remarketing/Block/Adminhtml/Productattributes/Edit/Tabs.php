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

class Listrak_Remarketing_Block_Adminhtml_Productattributes_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('productattributes_map_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('remarketing')->__('Map Attributes'));
    }

    public function _prepareLayout()
    {
        $attributeCodes = $this->_attributeSetAttributes(Mage::registry('productattribute_data')->getAttributeSetId());

        $this->addTab(
            'productattributes_brand',
            array(
                'label' => Mage::helper('remarketing')->__('Brand'),
                'content' => $this->getLayout()->createBlock(
                    'remarketing/adminhtml_productattributes_edit_tab_form_brand'
                )->setAttributeOptions($attributeCodes)->toHtml()
            )
        );

        $this->addTab(
            'productattributes_categories',
            array(
                'label' => Mage::helper('remarketing')->__('Category and Subcategory'),
                'content' => $this->getLayout()->createBlock(
                    'remarketing/adminhtml_productattributes_edit_tab_form_categories'
                )->setAttributeOptions($attributeCodes)->toHtml()
            )
        );
    }

    private function _attributeSetAttributes($setId)
    {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->setAttributeSetFilter($setId)
            ->addVisibleFilter();

        $attributes = array();
        foreach ($collection as $value) {
            $attributes[$value->getAttributeCode()] = $value->getFrontendLabel()
                . ' (' . $value->getAttributeCode() . ')';
        }

        asort($attributes);

        return $attributes;
    }
}

