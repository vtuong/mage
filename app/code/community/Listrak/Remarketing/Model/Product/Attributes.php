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

class Listrak_Remarketing_Model_Product_Attributes
{
    public function toOptionArray()
    {
        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addVisibleFilter();

        $attributes = array();
        foreach ($collection as $value) {
            array_push(
                $attributes,
                array(
                    'code' => $value->getAttributeCode(),
                    'label' => $value->getFrontendLabel()
                )
            );
        }

        // sort the attributes by label
        usort(
            $attributes, function ($a, $b) {
                $valA = $a['label'] . ':' . $a['code'];
                $valB = $b['label'] . ':' . $b['code'];
                return (($valA == $valB) ? 0 : (($valA < $valB) ? -1 : 1));
            }
        );

        $final = array();
        array_push($final, array('value' => '', 'label' => 'No Selection'));
        foreach ($attributes as $attribute) {
            array_push(
                $final,
                array(
                    'value' => $attribute['code'],
                    'label' => $attribute['label'] . ' (' . $attribute['code'] . ')'
                )
            );
        }

        return $final;
    }
}

