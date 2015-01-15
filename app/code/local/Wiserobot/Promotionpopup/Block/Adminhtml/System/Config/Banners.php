<?php
/*
NextWidgets **NOTICE OF LICENSE**
This source file is subject to the EULA that is bundled with this package in the file LICENSE.pdf. It is also available through the world-wide-web at this URL:
http://nextwidgets.com/magento_extension_license.pdf
=================================================================
MAGENTO COMMUNITY EDITION USAGE NOTICE
=================================================================
This package is designed for the Magento COMMUNITY edition
This extension may not work on any other Magento edition except Magento COMMUNITY edition. NextWidgets does not provide extension support in case of incorrect edition usage.
=================================================================
Copyright (c) 2011 NextWidgets – ALENSA AG (http://www.nextwidgets.com)
License http://nextwidgets.com/magento_extension_license.pdf
*/
?>
<?php
class Wiserobot_Promotionpopup_Block_Adminhtml_System_Config_Banners extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){
		$this->setElement($element);//vtuong write
    	$storeId=1;
    	if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getStore())){ // store level
		    $storeId = Mage::getModel('core/store')->load($code)->getStoreId();
		}elseif (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())){ // website level
		    $website_id = Mage::getModel('core/website')->load($code)->getId();
		    $storeId = Mage::app()->getWebsite($website_id)->getDefaultStore()->getStoreId();
		}else{ // default level
		    $storeId = 0;
		}

        $originalData = $element->getOriginalData();
        $this->addData(array(
            // 'frame_url'   => $originalData['frame_url']
        ));
        

		// echo "vtuong print test begin </br>";
  //       echo "<pre>";
  //           print_r(json_decode($element->getValue()));
  //       echo "</pre>";
  //       echo "vtuong print test end </br>";


        $value=array();
		if($v=$element->getValue()){
			$value=json_decode($v);
			foreach ($value as $key => $_value) {
				$value[$key]=str_replace('C:\fakepath\\', '', $_value);
			}
		}

		$html='';
		$html.="<div style=\"width:712px\"><button class=\"scalable add\" id=\"add_banner_group\" type=\"button\" ><span>Add banner group</span></button></div>";
		$html.="<div id=\"banner_form\" class=\"custom-options grid\">";
		if(count($value)>1){
			$html.='<div class="option-box">';
			$html.='<table style="width:100%" class="option-header"><thead><tr class="headings"><th class="opt-title">Title</th><th class="a-right">Action</th></tr></thead>';
			$html.='<tbody><tr><td><input type="text" class="title" name="title" style="width:100px" value="'.$value[0].'"/></td><td class="a-right"><button class="delete" type="button"><span>Del</span></button></td></tr></tbody>';
			$html.='</table>';
			$html.='<table style="width:100%" class="border"><thead><tr class="headings"><th>utm medium</th><th>utm campaign</th><th>utm term</th><th>utm source</th><th>Desktop banner</th><th>Mobile banner</th><th>url</th><th>Status</th><th>Action</th></tr></thead>';
			$html.='<tbody>';
			$html.='<tr><td><input class="utm_medium" name="utm_medium" style="width:70px" value="'.$value[1].'"></td><td><input class="utm_campain" name="utm_campain" style="width:70px" value="'.$value[2].'"></td><td><input class="utm_term" name="utm_term" style="width:70px" value="'.$value[3].'"></td><td><input class="utm_source" name="utm_source" style="width:70px" value="'.$value[4].'"></td><td><img src="'.Mage::getStoreConfig('web/unsecure/base_url').'media/banner/'.$value[5].'" style="height: 22px; width: 22px; float: left;" /><input type="file" class="file_upload" name="banner[1]"></td><td><img src="'.Mage::getStoreConfig('web/unsecure/base_url').'media/banner/'.$value[6].'" style="height: 22px; width: 22px; float: left;" /><input type="file" class="file_upload_m" name="banner_m[1]"></td><td><input class="url" name="url" style="width:70px" value="'.$value[7].'"></td>';
			if($value[8]==1)
				$html.='<td><select style="width:70px"><option value="1" SELECTED>enable</option><option value="0">disable</option></select></td><td><button type="button" class="delete"><span>Del</span></button></td></tr>';
			else
				$html.='<td><select style="width:70px"><option value="1">enable</option><option value="0" SELECTED>disable</option></select></td><td><button type="button" class="delete"><span>Del</span></button></td></tr>';
			if(count($value)>9){
				for($i=9;$i<count($value);$i=$i+9){

					$href='';
						//print_r($value);
					if(isset($value[$i-8]) && $value[$i-8])$href.="&utm_medium=".$value[$i-8];
					if(isset($value[$i-7]) && $value[$i-7])$href.="&utm_campaign=".$value[$i-7];
					if(isset($value[$i-8]) && $value[$i-6])$href.="&utm_term=".$value[$i-6];
					if(isset($value[$i-5]) && $value[$i-5])$href.="&utm_source=".$value[$i-5];
					$href=trim($href,'&');


					if($value[$i]==$value[$i-9]){
						$html.='<tr><td><input class="utm_medium" name="utm_medium" style="width:70px" value="'.$value[$i+1].'"></td><td><input class="utm_campain" name="utm_campain" style="width:70px" value="'.$value[$i+2].'"></td><td><input class="utm_term" name="utm_term" style="width:70px" value="'.$value[$i+3].'"></td><td><input class="utm_source" name="utm_source" style="width:70px" value="'.$value[$i+4].'"></td><td><img src="'.Mage::getStoreConfig('web/unsecure/base_url').'media/banner/'.$value[$i+5].'" style="height: 22px; width: 22px; float: left;" /><input type="file" class="file_upload" name="banner['.($i/9 +1).']"><td><img src="'.Mage::getStoreConfig('web/unsecure/base_url').'media/banner/'.$value[$i+6].'" style="height: 22px; width: 22px; float: left;" /><input type="file" class="file_upload_m" name="banner_m['.($i/9 +1).']"><td><input class="url" name="url" style="width:70px" value="'.$value[$i+7].'"></td></td>';
						if($value[$i+8]==1)
							$html.='<td><select style="width:70px"><option value="1" SELECTED>enable</option><option value="0">disable</option></select></td><td><button type="button" class="delete"><span>Del</span></button></td></tr>';
						else
							$html.='<td><select style="width:70px"><option value="1">enable</option><option value="0" SELECTED>disable</option></select></td><td><button type="button" class="delete"><span>Del</span></button></td></tr>';
					}else {						
						
						$html.='</tbody>';
						$promotionUrl=Mage::getUrl(null,array('_store'=>$storeId,'_nosid'=>true,'_query'=>$href));

						$html.='<tfoot><tr><td class="a-right" colspan="8"><button class="add" type="button"><span>Add banner</span></button></td></tr></tfoot></table><a target="_blank"href="'.$promotionUrl.'">'.$promotionUrl.'</a></div>';
						$html.='<div class="option-box">';
						$html.='<table style="width:100%" class="option-header">';
						$html.='<thead><tr class="headings"><th class="opt-title">Title</th><th class="a-right">Action</th></tr></thead>';
						$html.='<tbody><tr><td><input type="text" class="title" name="title" style="width:100px" value="'.$value[$i].'"></td><td class="a-right"><button class="delete" type="button"><span>Del</span></button></td></tr></tbody>';
						$html.='</table>';
						$html.='<table style="width:100%" class="border" ><thead><tr class="headings"><th>utm medium</th><th>utm campaign</th><th>utm term</th><th>utm source</th><th>Desktop banner</th><th>Mobile banner</th><th>url</th><th>Status</th><th>Action</th></tr></thead>';
						$html.='<tbody>';
						$html.='<tr><td><input name="utm_medium" class="utm_medium" style="width:70px" value="'.$value[$i+1].'"></td><td><input name="utm_campain" class="utm_campain" style="width:70px" value="'.$value[$i+2].'"></td><td><input name="utm_term" class="utm_term" style="width:70px" value="'.$value[$i+3].'"></td><td><input class="utm_source" name="utm_source" style="width:70px" value="'.$value[$i+4].'"></td><td><img src="'.Mage::getStoreConfig('web/unsecure/base_url').'media/banner/'.$value[$i+5].'" style="height: 22px; width: 22px; float: left;" /><input type="file" class="file_upload" name="banner['.($i/9 +1).']"></td><td><img src="'.Mage::getStoreConfig('web/unsecure/base_url').'media/banner/'.$value[$i+6].'" style="height: 22px; width: 22px; float: left;" /><input type="file" class="file_upload_m" name="banner_m['.($i/9 +1).']"></td><td><input name="url" class="url" style="width:70px" value="'.$value[$i+7].'"></td>';
						if($value[$i+8]==1)
							$html.='<td><select style="width:70px"><option value="1" SELECTED>enable</option><option value="0">disable</option></select></td><td><button type="button" class="delete"><span>Del</span></button></td></tr>';
						else
							$html.='<td><select style="width:70px"><option value="1">enable</option><option value="0" SELECTED>disable</option></select></td><td><button type="button" class="delete"><span>Del</span></button></td></tr>';
					}
				}
			}
			$html.='</tbody>';
			$html.='<tfoot><tr><td class="a-right" colspan="8"><button class="add" type="button"><span>Add banner</span></button></td></tr></tfoot></table>';

			$href='';
			$popuplength=count($value);
			//print_r($value);
			if(isset($value[$popuplength-8]) && $value[$popuplength-8])$href.="&utm_medium=".$value[$popuplength-8];
			if(isset($value[$popuplength-7]) && $value[$popuplength-7])$href.="&utm_campaign=".$value[$popuplength-7];
			if(isset($value[$popuplength-6]) && $value[$popuplength-6])$href.="&utm_term=".$value[$popuplength-6];
			if(isset($value[$popuplength-5]) && $value[$popuplength-5])$href.="&utm_source=".$value[$popuplength-5];
			$href=trim($href,'&');
			$promotionUrl=Mage::getUrl(null,array('_store'=>$storeId,'_nosid'=>true,'_query'=>$href));
			$html.='<a target="_blank" href="'.$promotionUrl.'">'.$promotionUrl.'</a>';
			$html.='</div>';
		}

		$html.="</div>";
		//$html.='<button class="scalable add" id="add_banner" type="button" ><span>Add Banners</span></button>';
		$html.='<style>
		#promotionpopup_settings input[type="file"]{width:100px;color:transparent}
		#row_promotionpopup_settings_banners .label{display:none}
		</style>';
		// $html.='<script type="text/javascript" src="/js/wiserobot/bannerform.js"></script>';
		$html.='<script type="text/javascript" src="/local/magento/js/wiserobot/bannerform.js"></script>';
		$html.='<script type="text/javascript">
		var bannerForm = new bannerForm("banner_form");
		</script>'."<input id='hidden_key' type='hidden' name='".$element->getName()."' value='".implode(',', $value)."'/>";
		$html.='<script type="text/javascript">
		bannerForm.getKey();
		</script>';
		return $html;
    }
}
