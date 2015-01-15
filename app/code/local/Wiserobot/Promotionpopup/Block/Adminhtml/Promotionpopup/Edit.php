<?php

class Wiserobot_Promotionpopup_Block_Adminhtml_Promotionpopup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'promotionpopup';
        $this->_controller = 'adminhtml_promotionpopup';
        
        $this->_updateButton('save', 'label', Mage::helper('promotionpopup')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('promotionpopup')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('promotionpopup_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'promotionpopup_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'promotionpopup_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('promotionpopup_data') && Mage::registry('promotionpopup_data')->getId() ) {
            return Mage::helper('promotionpopup')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('promotionpopup_data')->getTitle()));
        } else {
            return Mage::helper('promotionpopup')->__('Add Item');
        }
    }
}