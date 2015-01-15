<?php
/**
 * Listrak Remarketing Magento Extension Ver. 1.0.0
 *
 * PHP version 5
 *
 * @category  Listrak
 * @package   Listrak_Remarketing
 * @author    Listrak Magento Team <magento@listrak.com>
 * @copyright 2011 Listrak Inc
 * @license   http://s1.listrakbi.com/licenses/magento.txt License For Customer Use of Listrak Software
 * @link      http://www.listrak.com
 */

class Listrak_Remarketing_Block_Ecjs extends Mage_Core_Block_Text
{

    public function getPageName()
    {
        return $this->_getData('page_name');
    }

    public function getMatchingPages()
    {
        $fullMatches = array();

        try {
            $route = Mage::app()->getRequest()->getRouteName();
            $controller = Mage::app()->getRequest()->getControllerName();
            $action = Mage::app()->getRequest()->getActionName();

            $matches = array();
            $matches[] = '/' . $route . '/' . $controller . '/' . $action . '/';
            $matches[] = '/' . $route . '/' . $controller . '/' . $action;
            $matches[] = '/' . $route . '/' . $controller . '/*';
            $matches[] = '/' . $route . '/' . $controller . '/';
            $matches[] = '/' . $route . '/*/*';
            $matches[] = '/' . $route . '/*';
            $matches[] = '/*/*/*';
            $matches[] = '/*/*';
            $matches[] = '/*';
            $matches[] = '';

            if (strtolower($action) == 'index') {
                $matches[] = '/' . $route . '/' . $controller;
            }
            if (strtolower($action) == 'index' && strtolower($controller) == 'index') {
                $matches[] = '/' . $route;
            }

            foreach ($matches as $match) {
                $fullMatches[] = $match;
                if ($match && $match{0} && $match{0} == '/') {
                    $fullMatches[] = substr($match, 1);
                }
            }
        } catch (Exception $ex) {
            Mage::getModel("listrak/log")->addException($ex);
        }

        return $fullMatches;
    }

    protected function _toHtml()
    {
        if (!Mage::helper('remarketing')->coreEnabled()) {
            return "";
        }

        $collections = Mage::getModel('listrak/emailcapture')->getCollection()
        ->addFieldToFilter('page', $this->getMatchingPages());

        echo '<pre>';
        print_r($this->getMatchingPages());
        echo '</pre>';

        if ($collections && $collections->count()) {
            $html = array();
            $observed = array();

            $html[] = '<script type="text/javascript">';
            $html[] = 'arrEcjs = [];';
            $html[] = 'function _ecjs(ecid,email) { new Ajax.Request("' . $this->getAjaxUrl()
                . '", {parameters:{cid: ecid, email: email}}); '
                // .'console.log("varecid = "+ecid+" varemail= "+email);'
                .'}';
            $html[] = 'function ecjsInit() {' .
                'console.log("running ecjsInit function");'.
                'for(var ecjsi = 0; ecjsi < arrEcjs.length; ecjsi++) {' .
                'if($(arrEcjs[ecjsi].id)) {' .
                '$(arrEcjs[ecjsi].id).stopObserving("change", arrEcjs[ecjsi].fn);' .
                '$(arrEcjs[ecjsi].id).observe("change", arrEcjs[ecjsi].fn);' .
                '}' .
                '}' .
                '}';
            foreach ($collections as $observer) {
                if (!in_array($observer->getFieldId(), $observed)) {
                    $html[] = 'arrEcjs.push({id:"' . $observer->getFieldId()
                        . '", fn: function() { _ecjs(' . $observer->getEmailcaptureId()
                        . ', $(this).value);}'
                        .'});';
                    $observed[] = $observer->getFieldId();
                }
            }

            $html[] = 'document.observe("dom:loaded", function() { ecjsInit(); ';
            $html[] = 'Ajax.Responders.register({' .
                'onComplete: function() {' .
                'ecjsInit();' .
                '}' .
                '});});';

            if ($observed && count($observed) === 0) {
                return "";
            }

            $html[] = '</script>';
            return implode("\n", $html);
        }

        return "";
    }

    public function getAjaxUrl()
    {
        return Mage::getUrl(
            'remarketing/email',
            array('_secure' => Mage::app()->getStore()->isCurrentlySecure())
        );
    }
}
