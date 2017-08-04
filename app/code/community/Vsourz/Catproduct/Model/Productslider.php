<?php
class Vsourz_Catproduct_Model_Productslider extends Mage_Catalog_Model_Product{
	public function getItemCollection($catId,$prodCnt){
		$categoryId = $catId;
		$products = $prodCnt;
		$visibility = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_SEARCH);
				
		$collection = Mage::getSingleton('catalog/category')->load($categoryId)->getProductCollection()
            ->addAttributeToSelect('*')
			->setStoreId(Mage::app()->getStore()->getId())
			->addAttributeToFilter('visibility', $visibility)
			->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			->setOrder('position','ASC')
			->setPageSize($products);
		
		Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		return $collection;
	}
}
?>