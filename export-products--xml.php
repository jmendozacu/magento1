<?php
// Magento XML products exporter
// Version 0.1
// by Michele Marcucci
// http://www.michelem.org
 
require_once 'app/Mage.php';
umask( 0 );
Mage::app( "default" );
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 
$_magentoPath = "/var/virtual/magento";
$_urlPath = "";
$_imagePath = $_urlPath . "media";
$_logFileName = "export_products.log";
$_xmlPath = $_magentoPath . "/var/export";
 
Mage::log( "Export start", null, $_logFileName );
 
// Prepare collection
$_productCollection = Mage::getModel('catalog/product')->getCollection();
$_productCollection->addAttributeToSelect('*');
 
/* You can change and uncomment these lines below to filter your products collection */
 
// Filter by updated_at date, get only daily changes
//$_productCollection->addFieldToFilter('updated_at', array('from'=>date("Y-m-d", time()-86400)));
 
// Filter by product type, get only downloadables
//$_productCollection->addFieldToFilter('type_id',  array('like'=>'downloadable'));
 
// Filter by sku get only products with sku like "EBOOK-%"
//$_productCollection->addFieldToFilter('sku',  array('like'=>'EBOOK-%'));
 
// Limit output to 15 records
//$_productCollection->getSelect()->limit(15);
 
Mage::log( "Products to be exported: " . $_productCollection->count(), null, $_logFileName );
 
$i = 1;
foreach ( $_productCollection as $_product ) {
 
    // Prepare array of variables to grow XML file
    $v['sku'] = $_product->getSku();
    $v['product_name'] = $_product->getName();
    $v['type'] = $_product->getTypeId();
    $v['description'] = $_product->getDescription();
    $v['short_description'] = $_product->getShortDescription();
    $v['meta_title'] = $_product->getMetaTitle();
    $v['meta_description'] = $_product->getMetaDescription();
    $v['meta_keyword'] = $_product->getMetaKeyword();
    $v['created_at'] = $_product->getCreatedAt();
    $v['updated_at'] = $_product->getUpdatedAt();
    $v['url_path'] = $_urlPath . $_product->geturlpath();
    $v['image'] = $_imagePath . $_product->getImage();
    $v['image_label'] = $_product->getImageLabel();
    $v['price'] = $_product->getPrice();
    $v['special_price'] = $_product->getSpecialPrice();
    $v['weight'] = $_product->getWeight();
 
    // Get the Magento categories for the product
    $categoryIds = $_product->getCategoryIds();
 
    foreach($categoryIds as $categoryId) {
        $category = Mage::getModel('catalog/category')->load($categoryId);
        $v['categories'][$_product->getSku()][] = $category->getName();
    }
 
    // If product is downloadable get some informations about links added
    if ( $_product->getTypeId() == "downloadable" ) {
        $_links = Mage::getModel('downloadable/product_type')->getLinks( $_product );
        foreach ( $_links as $_link )
            $v['available_formats'][$_product->getSku()][] = $_link->getTitle();
    }
 
    // Prepare XML file to save
    $xmlFile = $_xmlPath . "/" . $_product->getSku() . ".xml";
     
    $doc = new DomDocument('1.0', 'UTF-8');
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;   
     
    $root = $doc->createElement('product');
    $root = $doc->appendChild($root);
     
    $occ = $doc->createElement('root');
    $occ = $root->appendChild($occ);
     
    foreach ( $v as $fieldName => $fieldValue ) {
        $child = $doc->createElement($fieldName);
        $child = $occ->appendChild($child);
 
        if ( is_array($fieldValue) ) {
            $value = $doc->createTextNode(implode( "|", $fieldValue[$_product->getSku()] ));
            $value = $child->appendChild($value);
        } else {
            $value = $doc->createTextNode($fieldValue);
            $value = $child->appendChild($value);
        }
 
    }
 
    // Save each product as XML file
    $doc->save( $xmlFile );
     
    Mage::log( "File " . $i . ": " . $_product->getSku(), null, $_logFileName );
     
    $i++;
}
 
Mage::log( "Export done", null, $_logFileName );