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

class Listrak_Remarketing_Block_Adminhtml_Notifications extends Mage_Core_Block_Text
{
    protected function _toHtml()
    {
        $html = "";

        if (!Mage::helper('remarketing')->checkSetupStatus()) {
            $html .= "<div class='notification-global'>The Listrak module requires a Listrak account. Please " .
                "<a href='http://www.listrak.com/partners/magento-extension.aspx'>fill out our form</a> to " .
                "get an account. If you already have a Listrak account, please contact your account manager " .
                "or <a href='mailto:support@listrak.com'>support@listrak.com</a>.</div>";
        }

        if (strpos(Mage::helper('core/url')->getCurrentUrl(), "/adminhtml_productattributes/") === false
            && Mage::helper('remarketing')->displayAttributeSetNotification()
        ) {
            $html .= "<div class='notification-global'>Brand attribute has not been defined for one or more " .
                "attribute sets. Please <a href='" .
                Mage::helper('adminhtml')->getUrl('remarketing/adminhtml_productattributes/index') .
                "'>click here</a>, or go to Listrak > Product Attributes " .
                "to review your current settings.</div>";
        }

        return $html;
    }
}
