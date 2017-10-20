<?php
/**
 * author: Ho Ngoc Hang <kemly.vn@gmail.com>
 * export all categories UPS site
 */
define('MAGENTO', dirname(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';
Mage::app();

$category = Mage::getModel('catalog/category');
$tree = $category->getTreeModel();
$tree->load();
$ids = $tree->getCollection()->getAllIds();
if ($ids){
    $file = "cates_ups.csv";
    file_put_contents($file, '"Category Id","Category Name","Category Page Title"' . PHP_EOL);
    foreach ($ids as $id){
        $cat = Mage::getModel('catalog/category');
        $cat->load($id);
        $entity_id = $cat->getEntityId();
        $name = $cat->getName();
        $url_path = $cat->getUrlPath();
        $title = $cat->getMetaTitle();
        //echo $entity_id.'-'.$name.'-'.$url_path.'<br/>';
        $string = '"' . $entity_id . '","' . $name .'","' . $title .'"'. PHP_EOL;
        file_put_contents($file, $string, FILE_APPEND);
    }
}
exit();