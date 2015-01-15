<?php

class Wiserobot_Promotionpopup_Block_Adminhtml_Promotionpopup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('promotionpopup_form', array('legend'=>Mage::helper('promotionpopup')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('promotionpopup')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('promotionpopup')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('promotionpopup')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('promotionpopup')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('promotionpopup')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('promotionpopup')->__('Content'),
          'title'     => Mage::helper('promotionpopup')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getPromotionpopupData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPromotionpopupData());
          Mage::getSingleton('adminhtml/session')->setPromotionpopupData(null);
      } elseif ( Mage::registry('promotionpopup_data') ) {
          $form->setValues(Mage::registry('promotionpopup_data')->getData());
      }
      return parent::_prepareForm();
  }
}