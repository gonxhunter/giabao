<?php
/**
 * Created by PhpStorm.
 * User: chutienphuc
 * Date: 13/12/2016
 * Time: 11:47
 */
require_once 'Mage/Checkout/controllers/CartController.php';
class Sutunam_Custom_AjaxController extends Mage_Checkout_CartController{
    public function indexAction(){
        $id = $this->getRequest()->getParam('id');
        $products = Mage::getModel('catalog/category')->load($id)
            ->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('visibility', 4)
            ->setOrder('created_date', 'ASC')
            ->setPageSize(2)
        ;
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
        
        $content = '<div class="newest-products" id="newest-'.$id.'">';
        $content .= '<ul>';
        foreach($products as $product){
            $content .= '<li>';
            $content .= '<a href="'.$product->getProductUrl().'">';
            $content .= '<img src="'.Mage::helper('catalog/image')->init($product, 'small_image')->resize(150).'" />';
            $content .= '<p class="detail">View detail</p>';
            $content .= '</a>';
            $content .= '<p class="price">'.Mage::helper('core')->currency($product->getPrice()).'</p>';
            $content .= '<p class="name">'.$product->getName().'</p>';
            $content .= '<button type="button" title="'.$this->__('Add to Cart').'" class="button btn-cart" onclick="setLocationAjax(\''.Mage::helper('checkout/cart')->getAddUrl($product).'\')">';
            $content .= '<span><span>'.$this->__('Add to cart').'</span></span>';
            $content .= '</button>';
            $content .= '</li>';
        }
        $content .= '</ul></div>';
        $this->getResponse()->setBody($content);
    }

    public function addAction()
    {
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
        if($params['isAjax'] == 1){
            $response = array();
            try {
                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');

                /**
                 * Check product availability
                 */
                if (!$product) {
                    $response['status'] = 'ERROR';
                    $response['message'] = $this->__('Unable to find Product ID');
                }

                $cart->addProduct($product, $params);
                if (!empty($related)) {
                    $cart->addProductsByIds(explode(',', $related));
                }

                $cart->save();

                $this->_getSession()->setCartWasUpdated(true);

                /**
                 * @todo remove wishlist observer processAddToCart
                 */
                Mage::dispatchEvent('checkout_cart_add_product_complete',
                    array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );

                if (!$cart->getQuote()->getHasError()){
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    //New Code Here
                    $this->loadLayout();
                    $sidebar_block = $this->getLayout()->getBlock('minicart_head');
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    $sidebar = $sidebar_block->toHtml();
                    $response['sidebar'] = $sidebar;

                    //Product info
                    $response['image'] = '<img src="'.Mage::helper('catalog/image')->init($product, 'small_image')->resize(150).'" />';
                    $response['name'] = $product->getName();
                    $response['price'] = Mage::helper('core')->currency($product->getPrice());
                    if($params['qty']){
                        $subtotal = $params['qty'] * $product->getPrice();
                    }else{
                        $subtotal = $product->getPrice();
                    }
                    $response['subtotal'] = Mage::helper('core')->currency($subtotal);
                    $response['remove'] = '<a href="'.Mage::getUrl( 'checkout/cart/delete', array( 'id' => $product->getId() ) ).'" data-confirm="'.$this->__('Are you sure you would like to remove this item from the shopping cart?').'" class="remove">'.$this->__("Remove this item").'</a>';

                    //Add related products
                    $categories =$product->getCategoryCollection()
                        ->setPage(1, 1)
                        //->addFieldToFilter(‘level’,"3")
                        //->addFieldToFilter(‘parent_id’,"3")
                        ->setOrder("level","desc")
                        ->load();
                    foreach ($categories as $_category) {
                        $products = Mage::getModel('catalog/category')->load($_category->getId())
                            ->getProductCollection()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('status', 1)
                            ->addAttributeToFilter('visibility', 4)
                            ->setOrder('rand()')
                            ->setPageSize(4);
                        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
                    }
                    $response['related'] = '';
                    if(count($products) > 0){
                        $content = '<h3>'.$this->__('Related product').'</h3>';
                        $content .= '<div class="related-products">';
                        $content .= '<ul>';
                        foreach($products as $product){
                            $content .= '<li>';
                            $content .= '<a href="'.$product->getProductUrl().'">';
                            $content .= '<img src="'.Mage::helper('catalog/image')->init($product, 'small_image')->resize(150).'" />';
                            $content .= '</a>';
                            $content .= '<p>'.$product->getName().'</p>';
                            $content .= '<button type="button" title="'.$this->__('Add to Cart').'" class="button btn-cart" onclick="setLocationAjax(\''.Mage::helper('checkout/cart')->getAddUrl($product).'\')">';
                            $content .= '<span><span>'.$this->__('Add to cart').'</span></span>';
                            $content .= '</button>';
                            $content .= '</li>';
                        }
                        $content .= '</ul></div>';
                        $response['related'] = $content;
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $msg = "";
                if ($this->_getSession()->getUseNotice(true)) {
                    $msg = $e->getMessage();
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $msg .= $message.'<br/>';
                    }
                }

                $response['status'] = 'ERROR';
                $response['message'] = $msg;
            } catch (Exception $e) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Cannot add the item to shopping cart.');
                Mage::logException($e);
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }else{
            return parent::addAction();
        }
    }

    public function updateAction()
    {        
        try {
            $cartData = $this->getRequest()->getParam('cart');
            $response = array();
            if (is_array($cartData)) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                foreach ($cartData as $index => $data) {
                    if (isset($data['qty'])) {
                        $cartData[$index]['qty'] = $filter->filter(trim($data['qty']));
                    }
                }
                $cart = $this->_getCart();
                if (! $cart->getCustomerSession()->getCustomer()->getId() && $cart->getQuote()->getCustomerId()) {
                    $cart->getQuote()->setCustomerId(null);
                }

                $cartData = $cart->suggestItemsQty($cartData);
                $cart->updateItems($cartData)
                    ->save();                
            }
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $items = $quote->getAllItems();
            $product = array();
            foreach ($items as $item) {
                $product[$item->getId()] = Mage::helper('checkout')->formatPrice($item->getRowTotalInclTax());
            }
            $response['item'] = $product;
            $response['result'] = 'success';
            $this->_getSession()->setCartWasUpdated(true);
            if (!$quote->validateMinimumAmount()) {
                $response['validateMinimumAmount'] = 'error';
            }
        } catch (Mage_Core_Exception $e) {
            $response['result'] = $e->getMessage();
        } catch (Exception $e) {
            $response['result'] = $e->getMessage();
        }

            if ($response['result'] == 'success'):
                $this->loadLayout();
                $sidebar_block = $this->getLayout()->getBlock('minicart_head');
                $sidebar = $sidebar_block->toHtml();
                $response['sidebar'] = $sidebar;
                $response['totals'] = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml')->toHtml();
            endif;

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}