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

class Listrak_Remarketing_Helper_Product
    extends Mage_Core_Helper_Abstract
{
    private $_parentsById = array();
    private $_attributeSets = null;
    private $_categories = array();
    private $_baseDir = null;
    private $_baseMediaPath = null;
    private $_imageModel = null;

    public function getProductEntity(Mage_Catalog_Model_Product $product, $storeId,
        $includeBrandAndCategory = true, $includeInventory = true,
        $includeConfigurableAttributes = true
    )
    {
        $result = array();

        $result['entity_id'] = $product->getEntityId();
        $result['sku'] = $product->getSku();
        $result['name'] = $product->getName();
        $result['price'] = $product->getPrice();
        $result['special_price'] = $product->getSpecialPrice();
        $result['special_from_date'] = $product->getSpecialFromDate();
        $result['special_to_date'] = $product->getSpecialToDate();
        $result['cost'] = $product->getCost();
        $result['description'] = $product->getDescription();
        $result['short_description'] = $product->getShortDescription();
        $result['weight'] = $product->getWeight();
        $result['url_key'] = $product->getUrlKey();
        if ($product->isVisibleInSiteVisibility()) {
            $result['url_path'] = $product->getUrlPath();
        }

        $brandAndCategoryProduct = $thumbnailProduct = $smallImageProduct = $imageProduct = $product;
        $parentProduct = $this->_getParentProduct($product);
        if ($parentProduct != null) {
            $result['parent_id'] = $parentProduct->getEntityId();
            $result['parent_sku'] = $parentProduct->getSku();

            if (!$product->isVisibleInSiteVisibility()) {
                $result['name'] = $parentProduct->getName();
                $result['url_path'] = $parentProduct->getUrlPath();
                $result['url_key'] = $parentProduct->getUrlKey();
            }

            $useParent = Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE == $parentProduct->getTypeId()
                && Mage::getStoreConfig(
                    Mage_Checkout_Block_Cart_Item_Renderer_Configurable::CONFIGURABLE_PRODUCT_IMAGE
                )
                    == Mage_Checkout_Block_Cart_Item_Renderer_Configurable::USE_PARENT_IMAGE;

            if (!$product->getData('image')
                || ($product->getData('image') == 'no_selection')
                || $useParent
            ) {
                $imageProduct = $parentProduct;
            }
            if (!$product->getData('small_image')
                || ($product->getData('small_image') == 'no_selection')
                || $useParent
            ) {
                $smallImageProduct = $parentProduct;
            }
            if (!$product->getData('thumbnail')
                || ($product->getData('thumbnail') == 'no_selection')
                || $useParent
            ) {
                $thumbnailProduct = $parentProduct;
            }
            
            if ($includeBrandAndCategory && !$product->isVisibleInSiteVisibility())
            {
                $brandAndCategoryProduct = $parentProduct;
            }

            if ($includeConfigurableAttributes
                && Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE == $parentProduct->getTypeId()
            ) {
                foreach (
                    $parentProduct
                        ->getTypeInstance(true)
                        ->getUsedProductAttributes($parentProduct) as $attribute) {
                    if (!array_key_exists('configurable_attributes', $result)) {
                        $result['configurable_attributes'] = array();
                    }
                    $attr = array();
                    $attr['attribute_name'] = $attribute->getFrontend()->getLabel();
                    $attr['value'] = $product->getAttributeText($attribute->getAttributeCode());
                    $result['configurable_attributes'][] = $attr;
                }
            }
        }
        $result['image'] = $this->_getProductImage($imageProduct, 'image');
        $result['small_image'] = $this->_getProductImage($smallImageProduct, 'small_image');
        $result['thumbnail'] = $this->_getProductImage($thumbnailProduct, 'thumbnail');

        if ($includeBrandAndCategory) {
            $setSettings = $this->_getProductAttributeSetSettings($brandAndCategoryProduct);

            if ($setSettings['brandAttribute'] != null) {
                $result['brand'] = $brandAndCategoryProduct->getAttributeText($setSettings['brandAttribute']);
            }

            if ($setSettings['catFromMagento']) {
                $categoryRootId = Mage::helper('remarketing')->getCategoryRootIdForStore($storeId);
                $categories = $this->_getCategoryIds($brandAndCategoryProduct, $categoryRootId);
                if (array_key_exists('category_id', $categories)) {
                    $result['category'] = $this->_getCategoryName($categories['category_id']);
                }
                if (array_key_exists('sub_category_id', $categories)) {
                    $result['sub_category'] = $this->_getCategoryName($categories['sub_category_id']);
                }
            } else {
                if ($setSettings['catFromAttributes']) {
                    if ($setSettings['categoryAttribute'] != null) {
                        $result['category'] = $brandAndCategoryProduct->getAttributeText($setSettings['categoryAttribute']);
                    }

                    if ($setSettings['subcategoryAttribute'] != null) {
                        $result['sub_category'] = $brandAndCategoryProduct->getAttributeText($setSettings['subcategoryAttribute']);
                    }
                }
            }
        }

        if ($includeInventory) {
            $result['in_stock'] = $product->isAvailable() ? "true" : "false";
            $stockItem = $product->getStockItem();
            if ($stockItem) {
                $result['qty_on_hand'] = $stockItem->getStockQty();
            }
        }

        $result['type'] = $product->getTypeId();

        return $result;
    }

    private function _getProductImage(Mage_Catalog_Model_Product $product,
        $imageType)
    {
        try {
            if ($this->_baseDir == null) {
                $this->_baseDir = Mage::getBaseDir();
            }
            if ($this->_baseMediaPath == null) {
                $this->_baseMediaPath = Mage::getSingleton('catalog/product_media_config')->getBaseMediaPath();
            }
            if ($this->_imageModel == null) {
                $this->_imageModel = Mage::getModel('catalog/product_image');
            }
            $this->_imageModel->setDestinationSubdir($imageType);
            $this->_imageModel->setBaseFile($product->getData($imageType));
            $imageBaseFile = $this->_imageModel->getBaseFile();
            if (!(false === strpos($imageBaseFile, $this->_baseMediaPath))) {
                return substr($imageBaseFile, strlen($this->_baseMediaPath));
            }
            return '/../../..' . substr($imageBaseFile, strlen($this->_baseDir));
        } catch (Exception $ex) {
             Mage::getModel("listrak/log")->addException(
                 'Failed to get product image for product '.$product->getEntityId().': '.$ex
             );
        }
        return null;
    }

    private function _getParentProduct(Mage_Catalog_Model_Product $product)
    {
        $parentIds = Mage::getModel('catalog/product_type_configurable')
            ->getParentIdsByChild($product->getEntityId());

        if (is_array($parentIds) && count($parentIds) > 0) {
            if (count($parentIds) > 1) {
                Mage::getModel("listrak/log")->addException(
                    "Product has multiple parents: sku=" . $product->getSku() . " parentIds=" . implode(
                        ', ', $parentIds
                    )
                );
            }

            $parentId = $parentIds[0];
            if ($parentId != null) {
                if (!array_key_exists($parentId, $this->_parentsById)) {
                    $this->_parentsById[$parentId] = Mage::getModel('catalog/product')
                        ->load($parentId);
                }
                return $this->_parentsById[$parentId];
            }
        }

        return null;
    }

    private function _getProductAttributeSetSettings(Mage_Catalog_Model_Product $product)
    {
        if ($this->_attributeSets == null) {
            $this->_attributeSets = array(0 => array(
                //default values
                'brandAttribute' => null,
                'catFromMagento' => true,
                'catFromAttributes' => false,
                'categoryAttribute' => null,
                'subcategoryAttribute' => null
            ));

            $attributeSetSettings = Mage::getModel('listrak/product_attribute_set_map')
                ->getCollection();
            foreach ($attributeSetSettings as $setSettings) {
                $this->_attributeSets[$setSettings->getAttributeSetId()] = array(
                    'brandAttribute' => $setSettings->getBrandAttributeCode(),
                    'catFromMagento' => $setSettings->finalCategoriesSource() == 'default',
                    'catFromAttributes' => $setSettings->finalCategoriesSource() == 'attributes',
                    'categoryAttribute' => $setSettings->getCategoryAttributeCode(),
                    'subcategoryAttribute' => $setSettings->getSubcategoryAttributeCode()
                );
            }
        }
        return array_key_exists($product->getAttributeSetId(), $this->_attributeSets)
            ? $this->_attributeSets[$product->getAttributeSetId()] : $this->_attributeSets[0];
    }

    private function _getCategoryIds(Mage_Catalog_Model_Product $product,
        $categoryRootId = null)
    {
        $categories = $product->getCategoryCollection();

        $lookUnder = array();
        $lookUnder[] = 1;
        if ($categoryRootId != null)
            $lookUnder[] = $categoryRootId;
        $path = $this->_getFirstPathByPosition($categories, 4, $lookUnder);
        
        $final = array();
        if (sizeof($path) > 2)
            $final['category_id'] = $path[2];
        if (sizeof($path) > 3)
            $final['sub_category_id'] = $path[3];

        return $final;
    }
    
    private function _getFirstPathByPosition($categoryCollection, $maxLevel, $underPath)
    {
        if (sizeof($underPath) >= $maxLevel)
            return $underPath;
            
        $nextCategory = array();
        foreach($categoryCollection as $category) {
            $pathIds = $category->getPathIds();

            if (sizeof($pathIds) > sizeof($underPath) && !in_array($pathIds[sizeof($underPath)], $nextCategory)) {
                $isUnderPath = true;
                for($i = 0; $i < sizeof($underPath); $i++)
                {
                    if ($pathIds[$i] != $underPath[$i])
                    {
                        $isUnderPath = false;
                        break;
                    }
                }
                
                if ($isUnderPath)
                    $nextCategory[] = $pathIds[sizeof($underPath)];
            }
        }

        if (sizeof($nextCategory) == 0)
            return $underPath;
            
        $winnerPath = array();
        $winnerPathPosition = 0;
        foreach($nextCategory as $category)
        {
            $testPath = $underPath;
            $testPath[] = $category;
            
            $testPathPosition = $this->_getCategoryPosition($category);
            
            if (sizeof($winnerPath) == 0 || $winnerPathPosition > $testPathPosition)
            {
                $winnerPath = $testPath;
                $winnerPathPosition = $testPathPosition;
            }
        }
        
        return $this->_getFirstPathByPosition($categoryCollection, $maxLevel, $winnerPath);
    }

    private function _getCategoryName($categoryId)
    {
        $category = $this->_getCategory($categoryId);
        
        if ($category != null)
        {
            return $category->getName();
        }
        
        return null;
    }
    
    private function _getCategoryPosition($categoryId)
    {
        $category = $this->_getCategory($categoryId);
        
        if ($category != null)
        {
            return $category->getPosition();
        }
        
        return null;
    }
    
    private function _getCategory($categoryId)
    {
        if (array_key_exists($categoryId, $this->_categories))
            return $this->_categories[$categoryId];
        else {
            $category = Mage::getModel('catalog/category')
                ->load($categoryId);
            
            if ($category != null)
            {
                $this->_categories[$categoryId] = $category;
                return $category;
            }
        }
        
        return null;
    }
}

