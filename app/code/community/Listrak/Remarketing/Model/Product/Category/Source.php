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

class Listrak_Remarketing_Model_Product_Category_Source
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'default', 'label' => 'Use Catalog Configuration (Default)'),
            array('value' => 'attributes', 'label' => 'Map from Product Attributes')
        );
    }
}

