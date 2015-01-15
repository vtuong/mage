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

class Listrak_Remarketing_Block_Adminhtml_ProductAttributes
    extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_productattributes';
        $this->_removeButton('add');

        $this->setTemplate('listrak/remarketing/productattributes.phtml');
    }

    public function _prepareLayout()
    {
        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('remarketing/adminhtml_productattributes_grid')
        );
        $this->setChild(
            'init_brands',
            $this->getLayout()->createBlock('remarketing/adminhtml_productattributes_init_brands')
        );
        return parent::_prepareLayout();
    }

    public function getInitBrandsHtml()
    {
        return $this->getChildHtml('init_brands');
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function setsWithoutBrandAttribute()
    {
        $sets = array();

        $allSets = Mage::registry('productattribute_sets');
        foreach ($allSets as $set) {
            if ($set->getBrandAttributeCode() == null) {
                array_push($sets, $set);
            }
        }

        return $sets;
    }
}

