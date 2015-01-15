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

class Wiserobot_listraknewsletter_Model_Emailcapture extends Mage_Core_Model_Abstract {

    public function _construct()    {
        parent::_construct();
        $this->_init('listraknewsletter/emailcapture');
    }
}