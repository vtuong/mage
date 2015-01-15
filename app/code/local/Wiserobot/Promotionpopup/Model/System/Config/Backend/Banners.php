<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * System config image field backend model
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Wiserobot_Promotionpopup_Model_System_Config_Backend_Banners extends Mage_Adminhtml_Model_System_Config_Backend_File
{
    /**
     * Getter for allowed extensions of uploaded files
     *
     * @return array
     */
    protected function _beforeSave(){

    	if (!empty($_FILES['banner']['tmp_name'])){

    		Mage::log("TH:1",null,'promotion.log');


			$uploadDir = $this->_getUploadDir();
			for($i=1;$i<=count($_FILES['banner']['tmp_name']);$i++){
				if($_FILES['banner']['tmp_name'][$i]){
					try {
						$file = array();
						$tmpName = $_FILES['banner']['tmp_name'];
						$file['tmp_name'] = $tmpName[$i];
						$name = $_FILES['banner']['name'];
						$file['name'] = $name[$i];
						$uploader = new Mage_Core_Model_File_Uploader($file);
						$uploader->setAllowedExtensions($this->_getAllowedExtensions());
						$uploader->setAllowRenameFiles(true);
						$result = $uploader->save($uploadDir);

						Mage::log("result=",null,'promotion.log');
						Mage::log($result,null,'promotion.log');
					} catch (Exception $e) {
						Mage::log("Exception TH1",null,'promotion.log');
						Mage::log($e,null,'promotion.log');
						Mage::throwException($e->getMessage());
						return $this;
					}
				}
			}
		}

		if (!empty($_FILES['banner_m']['tmp_name'])){

			Mage::log("TH:2",null,'promotion.log');


			$uploadDir = $this->_getUploadDir();
			for($i=1;$i<=count($_FILES['banner_m']['tmp_name']);$i++){
				if($_FILES['banner_m']['tmp_name'][$i]){
					try {
						$file = array();
						$tmpName = $_FILES['banner_m']['tmp_name'];
						$file['tmp_name'] = $tmpName[$i];
						$name = $_FILES['banner_m']['name'];
						$file['name'] = $name[$i];
						$uploader = new Mage_Core_Model_File_Uploader($file);
						$uploader->setAllowedExtensions($this->_getAllowedExtensions());
						$uploader->setAllowRenameFiles(true);
						$result = $uploader->save($uploadDir);

						Mage::log("result=",null,'promotion.log');
						Mage::log($result,null,'promotion.log');
						
					} catch (Exception $e) {
						Mage::log("Exception TH2",null,'promotion.log');
						Mage::log($e,null,'promotion.log');
						Mage::throwException($e->getMessage());
						$groups = $this->getValue();
				    	$uploadDir = $this->_getUploadDir();
						$value=explode(",", $groups);
						$this->setValue(json_encode($value));
						return $this;
					}
				}
			}
		}
		//Mage::log($this,null,'popup.log');
    
    	$groups = $this->getValue();
    	$uploadDir = $this->_getUploadDir();
		$value=explode(",", $groups);
		$this->setValue(json_encode($value));
		return $this;
    }
}
