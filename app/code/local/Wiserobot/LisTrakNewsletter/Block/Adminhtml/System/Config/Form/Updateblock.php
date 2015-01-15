<?php
/**
 * WISEROBOT INDUSTRIES SDN. BHD. **NOTICE OF LICENSE**
 * This source file is subject to the EULA that is bundled with this package in the file LICENSE.pdf. It is also available through the world-wide-web at this URL:
 * http://wiserobot.com/mage_extension_license.pdf
 * =================================================================
 * MAGENTO COMMUNITY EDITION USAGE NOTICE
 * =================================================================
 * This package is designed for the Magento COMMUNITY edition
 * This extension may not work on any other Magento edition except Magento COMMUNITY edition. WiseRobot does not provide extension support in case of incorrect edition usage.
 * =================================================================
 * Copyright (c) 2014 WISEROBOT INDUSTRIES SDN. BHD. (http://www.wiserobot.com)
 * License http://wiserobot.com/mage_extension_license.pdf
 *
 */
class Wiserobot_listraknewsletter_Block_Adminhtml_System_Config_Form_Updateblock extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    private $arraylist=array();
    private $arrayMage=array();
    private $json=array();

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        //get array types LisTrak and magento
        $this->setElement($element);
        $this->arraylist=Mage::getModel('listraknewsletter/listracktypes')->toOptionArray();
        $this->arrayMage=Mage::getModel('listraknewsletter/Magentotypes')->toOptionArray();
        $value=$element->getValue();
        $json=json_decode($value);
        $this->json=$json;

        echo "vtuong print test begin </br>";
        echo "<pre>";
            print_r($value);
        echo "</pre>";
        echo "vtuong print test end </br>";
       

//        echo '</br>dang o Config_Form_Updateblock| json = </br>';
//        print_r($json);

//        echo '$this->list = </br>';
//        print_r($this->list);
//        echo '</br>';

        $value=(string)$value;
        $value=str_replace('"', "'", $value);
        // echo '$value=(string)$value;='.$value;

        $output = '<script type="text/javascript">//<![CDATA[' . PHP_EOL;
        $output .= '    var updateblock_form_template = "' . str_replace('"', '\"', $this->_getNewRowHtml()) .'";' .PHP_EOL;
        $output .= '//]]></script>' . PHP_EOL;
                                      

        //input hidden luu tru doi truong json
        $output .= '<input hidden id="stringjson" name="' . $this->getElement()->getName() . '" value="'.$value.'">';
        $output .= '<table id="updateblock_container" style="border-collapse:collapse;"><tbody>';
        $output .= $this->_getHeaderHtml();
        //--------------------------------------------------------
        ?>

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js">
        </script>
        <script type="text/javascript">
            jQuery.noConflict();
            jQuery(document).ready(function(){
                var json=jQuery("#stringjson").val();
                jQuery("#listraknewsletter_api_attributes").attr({'value':json});
                console.log("json = ");
                console.log(json);
            });
        </script>

        <?php
        //-------------------------------------------------------
        if ($json) {
            foreach ($json as $key=>$values) {
                if(is_array($values)){
                    foreach ($values as $id => $value){
                        $output .= $this->_getRowHtml($id);
                    }
                }
                break;
            }
        }

        $output .= '<tr><td colspan="2" style="padding: 4px 0;">';
        $output .= $this->_getAddButtonHtml();
        $output .= '</td></tr>';
        $output .= '</tbody></table>';

        return $output;
    }

    protected function _getNewRowHtml()
    {

        $output = '<tr>';
        $output .= '<td style="padding: 2px 0;">';
        $output .=  '<select class="required-entry input-text" style="margin-right:10px" name="' . $this->getElement()->getName() . '[mage][]">';

        //type Magento
        $arrayMage=$this->arrayMage;
        if(is_array($arrayMage)){
            foreach($arrayMage as $listId => $listName){
                $output .=  '<option value="'.$arrayMage[$listId]["value"].'">';
                $output .=  $arrayMage[$listId]["label"] ;
                $output .=  '</option>';
            }
        }
        $output .=  '</select>';
        $output .= '</td>';

        $output .= '<td style="padding: 2px 0;">';
        $output .=  '<select class="required-entry input-text" name="' . $this->getElement()->getName() . '[list][]">';

        //type lisTrak
        $output .=  '<option selected value="">';
        $output .=  '</option>';
        $arraylist=$this->arraylist;
        if(is_array($arraylist)){
            foreach($arraylist as $listId => $listName){
//                echo '</br> listId = '.$list[$listId]["value"];
                $output .=  '<option value="'.$arraylist[$listId]["value"].'">';
                $output .=  $arraylist[$listId]["label"] ;
                $output .=  '</option>';
            }
        }
        $output .=  '</select>';

        $output .= '</td>';
        $output .= '<td style="padding: 2px 4px;">';
        $output .= $this->_getRemoveButtonHtml();
        $output .= '</td>';
        $output .= '</tr>';
        return $output;
    }



    protected function _getRowHtml($id = 0)
    {
        $output = '<tr>';
        $output .= '<td style="padding: 2px 0;">';
        $output .=  '<select class="required-entry input-text" style="margin-right:10px" name="' . $this->getElement()->getName() . '[mage][]">';

        $arrayMage=$this->arrayMage;
        $json=$this->json;

        if(is_array($arrayMage)){
            foreach($arrayMage as $listId => $listName){
                $output .=  '<option value="'.$arrayMage[$listId]["value"].'" ';
                if($json->mage[$id]==$arrayMage[$listId]["value"]){
                    $output .= ' selected ';
                }
                $output .='>';
                $output .=  $arrayMage[$listId]["label"] ;
                $output .=  '</option>';
            }
        }
        $output .=  '</select>';

        $output .= '</td>';
        $output .= '<td style="padding: 2px 0;">';

        //---------------------------------------------

        $output .=  '<select class="required-entry input-text" name="' . $this->getElement()->getName() . '[list][]">';
        $output .=  '<option value="">';
        $output .=  '</option>';
        
        $arraylist=$this->arraylist;
        $json=$this->json;

        //Types LisTrak
        if(is_array($arraylist)){
            foreach($arraylist as $listId => $listName){
                $output .=  '<option value="'.$arraylist[$listId]["value"].'" ';
                if($json->list[$id]==$arraylist[$listId]["value"]){
                    $output .= ' selected ';
                }
                $output .='>';
                $output .=  $arraylist[$listId]["label"] ;
                $output .=  '</option>';
            }
        }
        $output .=  '</select>';

        //-----------------------------------------------------------------------------------

        $output .= '</td>';
        $output .= '<td style="padding: 2px 4px;">';
        $output .= $this->_getRemoveButtonHtml();
        $output .= '</td>';
        $output .= '</tr>';
        return $output;
    }


    protected function _getHeaderHtml()
    {
        $helper = Mage::helper('listraknewsletter');
        $output = '<tr>';
        $output .= '<th style="padding: 2px; text-align: center;">';
        $output .= $helper->__("Magento's Fields");
        $output .= '</th>';
        $output .= '<th style="padding: 2px; text-align: center;">';
        $output .= $helper->__("LisTrak's Fields");
        $output .= '</th>';
        $output .= '<th>&nbsp;</th>';
        $output .= '</tr>';
        return $output;
    }


    protected function _getAddButtonHtml()
    {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('add')
            ->setLabel($this->__('Add Row'))
            ->setOnClick("Element.insert($(this).up('tr'), {before: updateblock_form_template})")
            ->toHtml();
    }

    protected function _getRemoveButtonHtml()
    {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('delete v-middle')
            ->setLabel($this->__('Delete'))
            ->setOnClick("Element.remove($(this).up('tr'))")
            ->toHtml();
    }
}