<?php
class Wiserobot_Promotionpopup_Block_Promotionpopup extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getPromotionpopup()     
     { 
        if (!$this->hasData('promotionpopup')) {
            $this->setData('promotionpopup', Mage::registry('promotionpopup'));
        }
        return $this->getData('promotionpopup');
        
    }
}