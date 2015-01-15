<?php

class Wiserobot_Promotionpopup_Block_Adminhtml_Promotionpopup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('promotionpopup_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('promotionpopup')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('promotionpopup')->__('Item Information'),
          'title'     => Mage::helper('promotionpopup')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('promotionpopup/adminhtml_promotionpopup_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}