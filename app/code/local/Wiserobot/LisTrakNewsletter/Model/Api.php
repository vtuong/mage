<?php
/*
WiseRobot Co.,LTD **NOTICE OF LICENSE**
This source file is subject to the EULA that is bundled with this package in the file LICENSE.pdf. It is also available through the world-wide-web at this URL:
http://wiserobot.com/mage_extension_license.pdf
=================================================================
MAGENTO COMMUNITY EDITION USAGE NOTICE
=================================================================
This package is designed for the Magento COMMUNITY edition
This extension may not work on any other Magento edition except Magento COMMUNITY edition. WiseRobot does not provide extension support in case of incorrect edition usage.
=================================================================
Copyright (c) 2013 Wiserobot Co.,LTD (http://www.wiserobot.com)
License http://wiserobot.com/mage_extension_license.pdf
*/
class Wiserobot_LisTrakNewsletter_Model_Api extends Mage_Core_Model_Abstract{

    public function _construct() {
        parent::_construct();
        $this->_init('listraknewsletter/api');
        $store=Mage::app()->getStore();
    }

    public function subscribeEmail($email, $storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return "";}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array(
                'ContactEmailAddress' => $email,
                'ListID' => $listId,
                'OverrideUnsubscribe' => true
            );
            $res = $soapClient->SubscribeContact($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                return $res;
            }
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->subscribeEmail ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return "";
        }
    }

    public function unsubscribeEmail($email,$storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return "";}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array(
                'ContactEmailAddress' => $email,
                'ListID' => $listId
            );
            $res = $soapClient->UnsubscribeContact($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                Mage::log($res,null, 'lisTrak.log');
                $res= $res->SubscribeContactResult;
                return $res;
            }
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->unsubscribeEmail ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return "";
        }
    }

    public function getContact($email,$storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return ;}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array(
                'ContactEmailAddress' => $email,
                'ListID' => $listId
            );
            $res = $soapClient->GetContact($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                Mage::log($res,null, 'lisTrak.log');
                return $res;
            }
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->getContact ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return ;
        }
    }

    public function getContactList($storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return ;}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array('ListID' => $listId);
            $res = $soapClient->GetContactList($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                Mage::log($res,null, 'lisTrak.log');
                return $res;
            }
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->getContactList ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return ;
        }
    }

    public function setContact($email, $contactProfileAttribute=array(), $storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        $externalEventIDs=Mage::getStoreConfig('listraknewsletter/modules/external_event_list',$store);
        if(!$username || !$password || !$listId || !$externalEventIDs){
            // if(!$externalEventIDs){ ?>               
                <!--<script type="text/javascript">
                    var msg='Field ExternalEventIDs in admin/configuration need save befor upload';
                    alert(msg);
                </script>--><?php
            // }
            // if(!$listId){ ?>               
                <!--<script type="text/javascript">
                    var msg='Field List in admin/configuration need save befor upload';
                    alert(msg);
                </script>--><?php
            // }
            return;
        }
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);

            $params = array(
                'WSContact' => array(
                    'EmailAddress'=>$email,
                    'ListID'=>(int)$listId,
                    'ContactProfileAttribute'=>$contactProfileAttribute
                ),
                'ProfileUpdateType'=>'Update',
                'ExternalEventIDs'=>(string)$externalEventIDs,
                'OverrideUnsubscribe'=>true
            );
            $res = $soapClient->SetContact($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                return $res;
                // return array('username'=>$username,'pass'=>$password, 'listId'=>$listId ,'return from sever'=>"",'message'=>$res);
                Mage::log($res,null, 'lisTrak.log'); 
            }           
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->setContact ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return;
            // return array('username'=>$username,'pass'=>$password, 'listId'=>$listId ,'exceptions from subscribeEmail'=>true,'message'=>$e);
        }
    }

    public function ReportSubscribedContacts($storeId=0,$page=1){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return;}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array(
                'ListID' => $listId,
                'Page'  =>$page
            );
            $res = $soapClient->ReportSubscribedContacts($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                return $res;
            }
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->ReportSubscribedContacts ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return ;
        }
    }

    public function getProfileHeaderCollection($storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return array();}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array('ListID'=>$listId);

            print_r($wsUser);


            $res = $soapClient->GetProfileHeaderCollection($params);
            $wsUser[]=array('ListID'=>$listId);
            $attributes=array();
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return array();
            }else{
                if(isset($res->GetProfileHeaderCollectionResult->WSProfileHeader)){
                    foreach ($res->GetProfileHeaderCollectionResult->WSProfileHeader as $header) {
                        foreach ($header->WSProfileAttributes as $att) {
                            //print_r($att);
                            $attributes[$att->Name]=$att->AttributeID;
                        }
                    }
                }
            }            
            return $attributes;
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->getProfileHeaderCollection ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return array();
        }
    }

    public function getContactListCollection($storeId=0) {
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password){return array();}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $res = $soapClient->GetContactListCollection(); 
            // print_r($res);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return array();
            }else{    
                foreach ($res->GetContactListCollectionResult->WSContactList as $header) {
                    $list[(string)$header->ListID] = (string)$header->ListName;
                }
                return $list;
            }
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->getProfileHeaderCollection ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return array();
        }        
    }

    public function reportRangeListContactRemoval($storeId,$startDate,$endDate,$page=1){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        if(!$username || !$password || !$listId){return;}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array(
                    'ListID'=>$listId,
                    'StartDate'=>$startDate,
                    'EndDate'=>$endDate,
                    'Page'=>$page
            );
            $res = $soapClient->ReportRangeListContactRemoval($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                return $res;    
            }        
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->reportRangeListContactRemoval ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return ;
        }
    }

    public function subscribeFormPassThrough($storeId=0,$sendDoubleOptIn,$sendWelcomeMessage){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);
        $externalEventIDs=Mage::getStoreConfig('listraknewsletter/modules/external_event_list',$store);
        if(!$username || !$password || !$listId || !$externalEventIDs){return;}
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2, 'encoding'=>'ISO-8859-1'));
            $soapClient->__setSoapHeaders($headers);
            $params = array(
                    'ExternalEventIDs'    =>$externalEventIDs,
                    'SendDoubleOptIn '    =>$sendDoubleOptIn,
                    'SendWelcomeMessage ' =>$sendWelcomeMessage
            );
            $res = $soapClient->SubscribeFormPassThrough($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return;
            }else{
                return $res; 
            }           
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->subscribeFormPassThrough ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return ;
        }
    }

    public function conductorGetExternalEvents($storeId=0){
        if($storeId){
            $store=Mage::getModel('core/store')->load($storeId);
        }else{
            $store=Mage::app()->getStore();
        }
        $username=Mage::getStoreConfig('listraknewsletter/modules/username',$store);
        $password=Mage::helper('core')->decrypt(Mage::getStoreConfig('listraknewsletter/modules/password',$store));
        $listId=Mage::getStoreConfig('listraknewsletter/modules/list',$store);

        if(!$username || !$password || !$listId){ return array(); }
        $wsUser = array(
            'UserName' => $username,
            'Password' => $password
        );
        try {
            $headers[] = new SoapHeader("http://webservices.listrak.com/v31/", 'WSUser', $wsUser );
            $soapClient = new SoapClient("https://webservices.listrak.com/v31/IntegrationService.asmx?WSDL", array('trace'=> 1, 'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2));
            $soapClient->__setSoapHeaders($headers);

            $params = array('ListID'=>(int)$listId);
            $res = $soapClient->ConductorGetExternalEvents($params);
            if(isset($res->WSException)){ ?>               
                <script type="text/javascript">
                    var msg="<?php echo $res->WSException->Description; ?>";
                    alert(msg);
                </script><?php
                return array();
            }else{
                $res=$res->ConductorGetExternalEventsResult;
                return $res;
            }
            // Mage::log($rest,null, 'lisTrak.log');            
        } catch (SoapFault $e) {
            Mage::log("has been exceptions api->conductorGetExternalEvents ",null,'listrak.log');
            Mage::log($e->getMessage(),null, 'listrak.log');
            return array();
            // return array('username'=>$username,'pass'=>$password, 'listId'=>$listId ,'exceptions from conductorGetExternalEvents'=>true,'message'=>$e);
        }
    }

}





    


