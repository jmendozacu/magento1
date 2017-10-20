<?php
/**
 * @copyright   Copyright (C) 2015 icotheme.com. All Rights Reserved.
 */
require_once 'Mage/Checkout/controllers/CartController.php';
class IcoTheme_AjaxProducts_IndexController extends Mage_Checkout_CartController
{
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

				if (!$product) {
					$response['status'] = 'ERROR';
					$response['message'] = $this->__('Unable to find Product ID');
				}

				$cart->addProduct($product, $params);
				if (!empty($related)) {
					$productarray = array_unique(explode(',', $related));
					$cart->addProductsByIds($productarray);
				}

				$cart->save();

				$this->_getSession()->setCartWasUpdated(true);

				Mage::dispatchEvent('checkout_cart_add_product_complete',
				array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
				);

				if (!$cart->getQuote()->getHasError()){
					$message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
					$response['status'] = 'SUCCESS';
					$response['message'] = $message;
					//New Code Here
					$this->loadLayout();
					//$toplink = $this->getLayout()->getBlock('top.links')->toHtml();
					$output = $this->getLayout()->getBlock('cart_header')->toHtml();
                    $cartbox = $this->getLayout()->createBlock('ajaxproducts/cartbox')
                        ->setProductId($params['product'])
                        ->toHtml();
                    /*$cartlayout = $this->getLayout()->createBlock('ajaxproducts/cartlayout')
                        ->setProductId($params['product'])
                        ->toHtml();*/
					//$this->getResponse()->setBody($output);
					Mage::register('referrer_url', $this->_getRefererUrl());
					$response['output'] = $output;
					//$response['toplink'] = $toplink;
					//$response['cartlayout'] = $cartlayout;
					$response['cartbox'] = $cartbox;
                    $response['pid'] = $params['product'];
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


    /**
     * Update customer's shopping cart
     */
    public  function updateAction()
    {
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
        $response = array();
        $id = null;
        $qty = null;
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }
            foreach($cart->getQuote()->getAllItems() as $item){
                if($item->getProductId()==$params['product']):
                    $id = $item->getItemId();
                    if($params['mode']=='plus'){
                        $params['qty'] = $item->getQty() + $params['qty'];
                    }else{
                        $params['qty'] = $item->getQty() - $params['qty'];
                    }

                endif;
            }
            if($id!=null){
                if($params['qty']==0){
                    $this->_getCart()->removeItem($id)->save();
                    $this->loadLayout();
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $output = $this->getLayout()->getBlock('ajaxproducts')->toHtml();
                    $cartlayout = $this->getLayout()->createBlock('ajaxproducts/cartlayout')
                        ->setProductId($params['product'])
                        ->toHtml();
                    $this->getResponse()->setBody($output);
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    $message = $this->__('Product removed to your shopping cart.');
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    $response['output'] = $output;
                    $response['toplink'] = $toplink;
                    $response['cartlayout'] = $cartlayout;
                    $response['pid'] = $params['product'];
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                    return;
                }
                $quoteItem = $cart->getQuote()->getItemById($id);
                if (!$quoteItem) {
                    Mage::throwException($this->__('Quote item is not found.'));
                }
                $item = $cart->updateItem($id, new Varien_Object($params));
                if (is_string($item)) {
                    Mage::throwException($item);
                }
                if ($item->getHasError()) {
                    Mage::throwException($item->getMessage());
                }
                $cart->save();
                $this->_getSession()->setCartWasUpdated(true);

                Mage::dispatchEvent('checkout_cart_update_item_complete',
                    array('item' => $item, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );
                if (!$this->_getSession()->getNoCartRedirect(true)) {
                    if (!$cart->getQuote()->getHasError()){
                        $message = $this->__('%s was updated in your shopping cart.', Mage::helper('core')->htmlEscape($item->getProduct()->getName()));
                        //$this->_getSession()->addSuccess($message);
                        $response['status'] = 'SUCCESS';
                        $response['message'] = $message;
                        //New Code Here
                        $this->loadLayout();
                        $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                        $output = $this->getLayout()->getBlock('ajaxproducts')->toHtml();
                        $cartlayout = $this->getLayout()->createBlock('ajaxproducts/cartlayout')
                            ->setProductId($params['product'])
                            ->toHtml();
                        $this->getResponse()->setBody($output);
                        Mage::register('referrer_url', $this->_getRefererUrl());
                        $response['output'] = $output;
                        $response['toplink'] = $toplink;
                        $response['cartlayout'] = $cartlayout;
                        $response['pid'] = $params['product'];
                    }
                }
            } else {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Cannot update the item.');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            }

        } catch (Exception $e) {
            $response['status'] = 'ERROR';

            $response['message'] = $this->__('Cannot update the item.');

            Mage::logException($e);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

	/**
	*
	* Add to cart product options, quickview
	*
	*/
	public function quickviewAction(){
		$productId = $this->getRequest()->getParam('product_id');
		$viewHelper = Mage::helper('catalog/product_view');
		$params = new Varien_Object();
		$params->setCategoryId(false);
		$params->setSpecifyOptions(false);
		try {
			$viewHelper->prepareAndRender($productId, $this, $params);
		} catch (Exception $e) {
			if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
				if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
					$this->_redirect('');
				} elseif (!$this->getResponse()->isRedirect()) {
					$this->_forward('noRoute');
				}
			} else {
				Mage::logException($e);
				$this->_forward('noRoute');
			}
		}
	}

	/**
	*
	* Delete product add to cart
	*
	*/
	public function deleteAction()
	{
		$id = (int) $this->getRequest()->getParam('id');
		$response = array();
		if ($id) {
			try {
				$this->_getCart()->removeItem($id)
					->save();
				$this->loadLayout();
				$toplink = $this->getLayout()->getBlock('top.links')->toHtml();
				$output = $this->getLayout()->getBlock('ajaxproducts')->toHtml();

				$this->getResponse()->setBody($output);
				Mage::register('referrer_url', $this->_getRefererUrl());
				$message = $this->__('Product removed to your shopping cart.');
				$response['status'] = 'SUCCESS';
				$response['message'] = $message;
				$response['output'] = $output;
				$response['toplink'] = $toplink;
			} catch (Exception $e) {
				$response['status'] = 'ERROR';
				$response['message'] = $this->_getSession()->addError($this->__('Cannot remove the item.'));
				Mage::logException($e);
			}
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
	}

	/**
	*
	* Update product edit add to cart
	*
	*/
	public function updateItemOptionsAction()
	{
		$cart   = $this->_getCart();
		$id = (int) $this->getRequest()->getParam('id');
		$params = $this->getRequest()->getParams();
		$response = array();
		if (!isset($params['options'])) {
			$params['options'] = array();
		}
		try {
			if (isset($params['qty'])) {
				$filter = new Zend_Filter_LocalizedToNormalized(
						array('locale' => Mage::app()->getLocale()->getLocaleCode())
				);
				$params['qty'] = $filter->filter($params['qty']);
			}

			$quoteItem = $cart->getQuote()->getItemById($id);
            Mage::log($quoteItem);
			if (!$quoteItem) {
				Mage::throwException($this->__('Quote item is not found.'));
			}

			$item = $cart->updateItem($id, new Varien_Object($params));
			if (is_string($item)) {
				Mage::throwException($item);
			}
			if ($item->getHasError()) {
				Mage::throwException($item->getMessage());
			}

			$related = $this->getRequest()->getParam('related_product');
			if (!empty($related)) {
				$cart->addProductsByIds(explode(',', $related));
			}

			$cart->save();

			$this->_getSession()->setCartWasUpdated(true);

			Mage::dispatchEvent('checkout_cart_update_item_complete',
			array('item' => $item, 'request' => $this->getRequest(), 'response' => $this->getResponse())
			);
			if (!$this->_getSession()->getNoCartRedirect(true)) {
				if (!$cart->getQuote()->getHasError()){
					$message = $this->__('%s was updated in your shopping cart.', Mage::helper('core')->htmlEscape($item->getProduct()->getName()));
					$this->_getSession()->addSuccess($message);
					$response['status'] = 'SUCCESS';
					$response['message'] = $message;
					//New Code Here
					$this->loadLayout();
					$toplink = $this->getLayout()->getBlock('top.links')->toHtml();
					$output = $this->getLayout()->getBlock('ajaxproducts')->toHtml();
					$this->getResponse()->setBody($output);
					Mage::register('referrer_url', $this->_getRefererUrl());
					$response['output'] = $output;
					$response['toplink'] = $toplink;
				}
			}
		} catch (Exception $e) {
			$response['status'] = 'ERROR';

			$response['message'] = $this->_getSession()->addError($this->__('Cannot update the item.'));

			Mage::logException($e);
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
		return;
	}

}