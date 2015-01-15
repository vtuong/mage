<?php
class Wiserobot_LisTrakNewsletter_Block_Adminhtml_Subscriber extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
  	Mage::log("da chay den Wiserobot_LisTrakNewsletter_Block_Adminhtml_Subscriber");
    $this->_controller = 'adminhtml_subscriber';
    $this->_blockGroup = 'listraknewsletter';
    $this->_headerText = Mage::helper('listraknewsletter')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('listraknewsletter')->__('Add Item');
    parent::__construct();
  }
}