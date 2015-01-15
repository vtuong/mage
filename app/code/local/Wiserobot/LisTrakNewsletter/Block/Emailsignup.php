<?php

class Wiserobot_listraknewsletter_Block_Emailsignup extends Mage_Core_Block_Text{

    // public function getPageName(){
    //     return $this->_getData('page_name');
    // }

    protected function _toHtml(){ 

        $html='<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>'."\n". 
            '<script type="text/javascript">'."\n".
            'var jQuery=jQuery.noConflict();'."\n".
            'function emailCapture(email) {'."\n". 
            'new Ajax.Request("' . 
            Mage::getUrl('listraknewsletter/emailcapture'). 
            '", {parameters:{email: email}}); }'."\n".  
            '</script>'."\n";        

        $merchantID = Mage::getStoreConfig('listraknewsletter/email_signup_popup/merchant_id');
        if ( !Mage::helper("listraknewsletter")->isEnabledModule()
            || !Mage::getStoreConfig('listraknewsletter/email_signup_popup/email_signup_popup_enabled')
            || strlen(Mage::getStoreConfig('listraknewsletter/email_signup_popup/script_location')) < 1
            // || strlen(trim($merchantID)) < 12
        ){ return ""; }

        if(Mage::getSingleton('core/session')->getShowsignup()==null){
            Mage::getSingleton('core/session')->setShowsignup("true");         
            $html.= '<script type="text/javascript">'."\n".           
            'var biJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");'."\n".
            'document.write(unescape("%3Cscript src=\'" + biJsHost + "' .
            Mage::getStoreConfig('listraknewsletter/email_signup_popup/script_location') .
            // '?m=' . $merchantID . '&v=1'.
            '\' type=\'text/javascript\'%3E%3C/script%3E"));' ."\n".                      
            '</script>'."\n";    
        }

        if(Mage::getStoreConfig("listraknewsletter/email_signup_popup/input_id_capture_email") 
            && (Mage::getStoreConfig("listraknewsletter/email_signup_popup/input_id_capture_email")!="")
            ){
            $arrfieldsEmailCapture=explode(',',Mage::getStoreConfig("listraknewsletter/email_signup_popup/input_id_capture_email"));
            $html.='<script type="text/javascript">' ."\n".
                'jQuery(document).ready(function(){' ."\n".
                    'jQuery("form").submit(function(){' ."\n";
                    foreach ($arrfieldsEmailCapture as $f) {
                        $html.='if(jQuery("#'.$f.'").length){' ."\n".   
                            'var email=jQuery("#'.$f.'").val();'."\n".                     
                            'emailCapture(email);'."\n".
                        '}' ."\n";
                    }                   
                $html.='});' ."\n".
                '});' ."\n".
            '</script>';
        }          

        return $html;   
    }
}
