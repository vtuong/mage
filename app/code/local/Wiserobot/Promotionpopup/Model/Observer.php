<?php

class Wiserobot_Productoptionstatus_Model_Observer extends Mage_Core_Block_Abstract
{
    public  function updateoptiontype($observer)
    {
        $event = $observer->getEvent();
print_r($event);exit;
        $_product=$event->getProduct();
$product=Mage::getModel('catalog/product')->load($_product->getId());
print_r($product->getOptions());exit;
        $_collection=Mage::getModel('productoptionstatus/productoptiontype')->getCollection()->addFieldToFilter('product_id', $product->getId())->addFieldToFilter('option_type_id',-1);
$options=$product->getOptionInstance()->getValueInstance();
echo "<pre>";
print_r($options);exit;
echo "0-";
        foreach($_collection as $_pro)
        {
echo "1-";
            foreach($options as $_option)
            {
echo "2-<pre>";print_r($_option);
              foreach($_option['value'] as $_optionvalue)
              {
echo "3-";
                  if($_optionvalue->getTitle()==$_pro->getTitle()){
echo "3.1-";
                      $_pro->setOptionTypeId($_optionvalue->getId());
                      $_pro->save();
         
                  }
              }
            }
        }
        exit;

            return $this;
    }
    public function updatestatus($observer)
    {
        $event = $observer->getEvent();
        $product=$event->getProduct();
        $request=$this->getRequest();
        $post=$request->getPost();
        $productpost=$request->getPost('product');
        $optionproductpost=$request->getPost('option_products');


//        Mage::getSingleton('adminhtml/session')->addSuccess($options['options']);
//        Mage::getSingleton('adminhtml/session')->addSuccess($productpost->getOptions());



        if(isset($productpost['options']))
        {
            foreach($productpost['options'] as $key=>$optiontype)
            {
                foreach($optiontype['values'] as $k=>$option)
                {
                    $optionstatus= Mage::getModel('productoptionstatus/productoptionstatus');
                    $_collection=$optionstatus->getCollection()->addFieldToFilter('option_type_id', $k)->getFirstItem();
                    if($_collection->getOptionStatusId())
                    {
                        $optionstatus->load($_collection->getOptionStatusId());//setOptionStatusId($_collection->getOptionStatusId());
                    }
                    $optionstatus->setOptionTypeId($k);
                    $optionstatus->setStoreId(1);
                    $optionstatus->setStatus($option['status']);
                    //$optionstatus->setQty($option['qty']);
                    //$optionstatus->setStockLevel($option['stock_level']);
                    $optionstatus->save();
                }
            }
        }


        if(isset($optionproductpost['id']))
        {
            foreach($optionproductpost['id'] as $_id => $_product)
            {

                $_optionproduct=Mage::getModel('productoptionstatus/productoption')->load($_id);

                if(!$_optionproduct->getId())
                {
                    $_optionproduct= Mage::getModel('productoptionstatus/productoption');
                    //$_optionproduct->setId($_id);
                    //$_optionproduct->isObjectNew(true);
                }
                $optionValues=array();
                foreach($_product['options'] as $_optionId =>$_optionValue)
                {
                    $optionValues[]=array($_optionValue['value'],$_optionValue['title']);
                }
                $_optionproduct->setOptionIds($optionValues);

                $_optionproduct->setQty($_product['qty']);
                $_optionproduct->setSku($_product['sku']);
                $_optionproduct->setGtin($_product['gtin']);
                $_optionproduct->setIsInstock($_product['is_instock']);
                $_productId=Mage::getModel('catalog/product')->getIdBySku($post['product']['sku']);//load($post['product']['sku'])->getId();
                $_optionproduct->setParentId($_productId);                
                $_optionproduct->setParentProductId($_productId);
                $_optionproduct->save();
            }
        }
        return $this;
    }

    public function checkQty($observer){
        Mage::log('Processed observer->checkQty',null,'promotion.log');
        $event = $observer->getEvent();
        $item=$event->getItem();

        $optiontypes=array();
        if(!count($item->getOptions())){return;}

            foreach($item->getOptions() as $_option){
                $optionData=$_option->getData();
                if(($optionData['code']!='info_buyRequest')&&($optionData['code']!='option_ids')) $optiontypes[]= $optionData['value'];
            }

            if(!$optiontypes)return;
            $_collection=Mage::getModel('productoptionstatus/productoptiontype')->getCollection()->addFieldToFilter('product_id', $item->getProductId())->addFieldToFilter('option_type_id',array('in',$optiontypes));

            foreach($_collection as $_productOptionType){
              $productOption= Mage::getModel('productoptionstatus/productoption')->load($_productOptionType->getProductOptionId());
              if($productOption->getId()){

                  if($productOption->getQty() < $item->getQty()){
                    Mage::log($productOption->getQty().'|'.$item->getQty(),null,'promotion.log');

                    $item->getUseOldQty(1);
                    Mage::throwException(
                        Mage::helper('sales')->__('The requested quantity for "'.$item->getProduct()->getName().'" is not available.')
                    );
                    return ;
                  }
              }
           }
        return ;
    }
/*
    public function updateQty($observer)
    {
        $event = $observer->getEvent();
        $order=$event->getOrder();
	      $items=$order->getAllVisibleItems();
        foreach($items as $_item) {
            $itemInfo = $_item->getProductOptions();
            if (isset($itemInfo['options'])) {
                $productOptions = $itemInfo['options'];
            }
            else {
                $productOptions = array();
            }
            for ($i = 0; $i < $_item->getQtyOrdered(); $i++) {
                foreach ($productOptions as $option) {
//                    $list .= "|nw_option|" . $option['option_id'] . "|nw_ps|" . $option['value'];
                      $optionstatus= Mage::getModel('productoptionstatus/productoptionstatus');
                      $_collection=$optionstatus->getCollection()->addFieldToFilter('option_type_id', $option['option_id'])->getFirstItem();
                      if($_collection->getOptionStatusId())
                      {
                          $optionstatus->load($_collection->getOptionStatusId());//setOptionStatusId($_collection->getOptionStatusId());
                          $optionstatus->setQty($optionstatus->getQty()-$item->getQty());
                          $optionstatus->save()
                      }


                }
            }
        }

        return $this;
    }
*/
}
