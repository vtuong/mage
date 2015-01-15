<?php

class Wiserobot_LisTrakNewsletter_Block_Checkout_Onepage_Bulling extends Mage_Checkout_Block_Onepage_Billing{
	
	// protected function _construct()	{   
	//     parent::_construct();   
	//     $this->getCheckout()->setStepData('billing', 'allow', false); 
	// }

	protected function _toHtml() {
        if (!$this->getTemplate()) { return ''; }
        $html = $this->renderView();
        $html.='<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>'."\n". 
            '<script type="text/javascript">'."\n".
            'var $jq=jQuery.noConflict();'."\n".
        	'$jq("document").ready(function(){'."\n".
        		'console.log("hu da thanh cong roi !");'."\n".
        		'$jq("#billing-new-address-form").append(\'<li>'.
				        '<input type="checkbox" name="billing[is_subscribed]" value="1" title="Sign Up for Newsletter" id="billing:is_subscribed" checked="checked">'.
				        '<label style="font-weight:normal;">'.
				            '&nbsp;&nbsp; I want to receive emails regarding exclusive customer-only discounts and special promotions.'.
				        '</label>'.
				    '</li>\');'."\n".
        	'});'."\n".
			'</script>';
        return $html;
    }
	
}