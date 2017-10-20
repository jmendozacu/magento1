<?php

/*
 * Created on : Jun 19, 2017, 3:44:14 PM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */



ini_set('display_errors', '1');
ini_set('memory_limit', '999999999M');
set_time_limit(0);
require '../app/Mage.php';
Mage::app();

$products = getProductId();

//Process create new Side
$path_media = Mage::getBaseDir('media');
$path_product_image = $path_media . '/catalog/product';
$total_pIds = count($products);
//echo $total_pIds;
//exit();
$xml = '<?xml version="1.0" encoding="utf-8"?>
<Data>';
$iDefaultStoreId = Mage::app()
        ->getWebsite(true)
        ->getDefaultGroup()
        ->getDefaultStoreId();
if ($total_pIds > 0) {
    $i = 1;
    foreach ($products as $product_id) {
        $p = Mage::getModel('catalog/product')->load($product_id);
        // echo $product_id;
        //print_r(Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product_id));
        if (count(Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product_id)) == 0) {
            if ($p->getStoreId() == $iDefaultStoreId) {
                $pName = $p->getName();
                $pSku = $p->getSku();
                $pGender = $p->getResource()->getAttribute('gender')->getFrontend()->getValue($p);
                $pColour = $p->getResource()->getAttribute('color')->getFrontend()->getValue($p);
                $currentCategoryId = $p->getCategoryIds();
                $pCateName = getCategoryName($currentCategoryId);
                $pDes = $p->getDescription();
                //process get main image

                $pImages = getImages($p);
                $pRelatedcolourways = getRelatedcolourways($p);
                $childProducts = '';
                if ($p->getTypeId() == 'configurable') {
                    $childProducts = Mage::getModel('catalog/product_type_configurable')
                            ->getUsedProducts(null, $p);
                    $pChildproducts = getChildAssociatedProducts($childProducts);
                } elseif ($p->getTypeId() == 'grouped') {
                    $childProducts = Mage::getModel('catalog/product_type_grouped')
                            ->getAssociatedProducts($p);
                    $pChildproducts = getChildAssociatedProducts($childProducts);
                }
                $xml .= '
		<row>';
                $xml .= '<name>' . $pName . '</name>
		<sku>' . $pSku . '</sku>
		<gender>' . $pGender . '</gender>
		<colour>' . $pColour . '</colour>
		<category>' . $pCateName . '</category>
		<description><![CDATA[' . $pDes . ']]></description>'
                        . $pImages
                        . $pChildproducts
                        . $pRelatedcolourways;

                $xml .= '</row>';
            }
            echo '===>Item ' . $i . '=>Product id: ' . $product_id . ' =>Store id: ' . $p->getStoreId() . " \n";
            $i++;
        }
    }
    $xml .= '</Data>';
    file_put_contents(dirname(__FILE__) . '/products.xml', $xml);
    echo '===>Total product:' . $total_pIds;
    exit();
} else {
    echo 'No data';
    exit();
}

/**
 * Get all productID 
 * @return type
 */
function getProductId() {
    $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('status', array('eq' => 1))
            ->joinField(
                    'is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left'
            )
            ->addAttributeToFilter('is_in_stock', array('eq' => 1));
    //->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_status=1', 'left');
    //->addAttributeToFilter('qty', array("gt" => 0));

    $pIds = $products->getAllIds();
    if (count($pIds) > 0) {
        return $pIds;
    } else {
        return array();
    }
}

/**
 * Get all productID by category
 * @param type $category_id
 * @return type
 */
function getProductIdByCategory($category_id) {
    if (empty($category_id))
        return array();
    $category = Mage::getModel('catalog/category')->load($category_id);
    $pIds = Mage::getModel('catalog/product')
            ->getCollection()
            ->addCategoryFilter($category)
            ->getAllIds();
    if (count($pIds) > 0) {
        return $pIds;
    } else {
        return array();
    }
}

/**
 * Get all 
 */
function getCategoryName($categories) {
    if (count($categories) == 0)
        return '';
    $name = '';
    for ($i = 0; $i < count($categories); $i++) {
        $category = Mage::getModel('catalog/category')->load($categories[$i]);
        $dot = $i < (count($categories) - 1) ? ',' : '';
        $name .= $category->getName() . '' . $dot;
    }
    return $name;
}

function getImages($product) {
    $images = $product->getMediaGalleryImages();
    if (count($images) == 0) {
        if (Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/catalog/product' . $product->getImage() != '') {
            return '<images>' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage() . '</images>';
        } else {
            return '';
        }
    }
    $urlimages = '
		<images>';
    $i = 1;
    foreach ($images as $key => $value) {
        $urlimages .= '<image' . $i . '>' . $value['url'] . '</image' . $i . '>';
        $i++;
    }
    $urlimages .= '</images>
		';
    return $urlimages;
}

function getRelatedcolourways($product) {
    $RelatedProductIds = $product->getRelatedProductIds();
    if (count($RelatedProductIds) > 0) {
        for ($i = 0; $i < count($RelatedProductIds); $i++) {
            $p = Mage::getModel('catalog/product')->load($RelatedProductIds[$i]);
            $inStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($p)->getIsInStock();
            if ($inStock) {
                $dot = $i < (count($RelatedProductIds) - 1) ? ',' : '';
                $relatedcolourways .= $p->getSku() . '' . $dot;
            }
        }
        if ($relatedcolourways != '')
            return '
		<relatedcolourways>' . $relatedcolourways . '</relatedcolourways>
		';
        else
            return '';
    } else {
        return '';
    }
}

function getChildAssociatedProducts($product) {

    if (count($product) > 0) {
        foreach ($product as $p) {
            $pChildren = Mage::getModel('catalog/product')->load($p->getId());
            $inStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($pChildren)->getIsInStock();
            if ($inStock) {
                $pText .= '<product>
				<sku>' . $pChildren->getSku() . '</sku>
				<name>' . $pChildren->getName() . '</name>
			</product>';
            }
        }
        if ($pText != '')
            return '
		<childproducts>' . $pText . '</childproducts>
		';
        else
            return '';
    } else {
        return '';
    }
}

exit();
?>
