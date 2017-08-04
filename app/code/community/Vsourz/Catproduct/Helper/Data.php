<?php
class Vsourz_Catproduct_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getPaginationBgColor(){
		$pagbgcolor = Mage::getStoreConfig('vsourz_catproduct/settings/paginationcolor');
		return $pagbgcolor;
	}
	public function getPaginationBgHoverColor(){
		$pagbghovercolor = Mage::getStoreConfig('vsourz_catproduct/settings/paginationhovercolor');
		return $pagbghovercolor;
	}
	public function getNavigationBgColor(){
		$navbgcolor = Mage::getStoreConfig('vsourz_catproduct/settings/navigationcolor');
		return $navbgcolor;
	}
	public function getNavigationBgHoverColor(){
		$navbghovercolor = Mage::getStoreConfig('vsourz_catproduct/settings/navigationhovercolor');
		return $navbghovercolor;
	}
	public function getDesktop(){
		$desktop = Mage::getStoreConfig('vsourz_catproduct/settings/desktop');
		return $desktop;
	}
	public function getIpadland(){
		$ipadland = Mage::getStoreConfig('vsourz_catproduct/settings/ipadland');
		return $ipadland;
	}
	public function getIpadport(){
		$ipadport = Mage::getStoreConfig('vsourz_catproduct/settings/ipadport');
		return $ipadport;
	}
	public function getSmallland(){
		$smallland = Mage::getStoreConfig('vsourz_catproduct/settings/smallland');
		return $smallland;
	}
	public function getSmallport(){
		$smallport = Mage::getStoreConfig('vsourz_catproduct/settings/smallport');
		return $smallport;
	}
	public function getAutoplay()
	{
		$autoplay = Mage::getStoreConfig('vsourz_catproduct/settings/autoplay');
		return $autoplay;
	}
	public function getNavigation()
	{
		$navigation = Mage::getStoreConfig('vsourz_catproduct/settings/navigation');
		return $navigation;
	}
	public function getPagination()
	{
		$pagination = Mage::getStoreConfig('vsourz_catproduct/settings/pagination');
		return $pagination;
	}
	
	public function getWidth()
	{
		$width=Mage::getStoreConfig('vsourz_catproduct/productsettings/imagewidth');
		return $width;
	}
	public function getHeight()
	{
		$height=Mage::getStoreConfig('vsourz_catproduct/productsettings/imageheight');
		return $height;
	}
	public function getRating()
	{
		$rating = Mage::getStoreConfig('vsourz_catproduct/productsettings/rating');
		return $rating;
	}
	public function getCartbutton()
	{
		$cartbutton = Mage::getStoreConfig('vsourz_catproduct/productsettings/cartbutton');	
		return $cartbutton;
	}
	public function getWishlist()
	{
		$wishlist = Mage::getStoreConfig('vsourz_catproduct/productsettings/wishlist');
		return $wishlist;
	}
	public function getCompare()
	{
		$compare = Mage::getStoreConfig('vsourz_catproduct/productsettings/compare');
		return $compare;
	}
	public function getNewProductText()
	{
		$newproduct = Mage::getStoreConfig('vsourz_catproduct/productsettings/new_product');
		return $newproduct;
	}public function getSaleProductText()
	{
		$saleproduct = Mage::getStoreConfig('vsourz_catproduct/productsettings/sale_product');
		return $saleproduct;
	}
	public function getImg($product, $w, $h, $imgVersion='image', $file=NULL)
	{
		$url = '';
		if ($h <= 0)
		{
			$url = Mage::helper('catalog/image')
				->init($product, $imgVersion, $file)
				->constrainOnly(true)
				->keepAspectRatio(true)
				->keepFrame(false)
				//->setQuality(90)
				->resize($w);
		}
		else
		{
			$url = Mage::helper('catalog/image')
				->init($product, $imgVersion, $file)
				->resize($w, $h);
		}
		return $url;
	}
	function altImage($product, $val, $w, $h, $imgVersion='small_image'){
		$product->load('media_gallery');
		$column = 'position';
		$value = $val;
		$gal = $product->getMediaGalleryImages();
		if ($gal = $product->getMediaGalleryImages())
		{
			if ($altImg = $gal->getItemByColumnValue($column, $value))
			{
				return
				'<div class="alt-imgage">
				<img class="alt-img lazyOwl"   src="' . $this->getImg($product, $w, $h, $imgVersion, $altImg->getFile()) . '" alt="' . $product->getName() . '" /></div>';
				
			}
		}
		return '';
	}
	
}
?>