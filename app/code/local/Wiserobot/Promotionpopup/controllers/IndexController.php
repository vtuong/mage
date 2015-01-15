<?php
class Wiserobot_Promotionpopup_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/promotionpopup?id=15 
    	 *  or
    	 * http://site.com/promotionpopup/id/15 	
    	 */
    	/* 
		$promotionpopup_id = $this->getRequest()->getParam('id');

  		if($promotionpopup_id != null && $promotionpopup_id != '')	{
			$promotionpopup = Mage::getModel('promotionpopup/promotionpopup')->load($promotionpopup_id)->getData();
		} else {
			$promotionpopup = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($promotionpopup == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$promotionpopupTable = $resource->getTableName('promotionpopup');
			
			$select = $read->select()
			   ->from($promotionpopupTable,array('promotionpopup_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$promotionpopup = $read->fetchRow($select);
		}
		Mage::register('promotionpopup', $promotionpopup);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}