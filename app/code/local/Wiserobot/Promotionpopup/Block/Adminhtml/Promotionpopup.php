<?php
class Wiserobot_Promotionpopup_Block_Adminhtml_Promotionpopup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_promotionpopup';
    $this->_blockGroup = 'promotionpopup';
    $this->_headerText = Mage::helper('promotionpopup')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('promotionpopup')->__('Add Item');
    parent::__construct();
  }
}