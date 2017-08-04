<?php
class Vsourz_Catproduct_Block_Productslider extends Mage_Catalog_Block_Product_Abstract{
	public function getProducts(){
		$catId = $this->getCategoryId();
		$prodCnt = $this->getProductCount();
		$_productCollection = Mage::getModel("vsourz_catproduct/productslider")->getItemCollection($catId,$prodCnt);
		return $_productCollection;
	}
}