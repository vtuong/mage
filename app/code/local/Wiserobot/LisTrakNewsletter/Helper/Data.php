<?php

class Wiserobot_Listraknewsletter_Helper_Data extends Mage_Core_Helper_Abstract {
	public function isEnabledModule() {
        return Mage::getStoreConfig('listraknewsletter/general/enabled');
    }
}