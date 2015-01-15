<?php
class Wiserobot_LisTrakNewsletter_Block_Subscriber extends Mage_Core_Block_Template
{
	public function _prepareLayout(){
		return parent::_prepareLayout();
    }
    
    public function getLisTrakNewsletter(){ 
        if (!$this->hasData('listraknewsletter')) {
            $this->setData('listraknewsletter', Mage::registry('listraknewsletter'));
        }
        return $this->getData('listraknewsletter');
        
    }
}