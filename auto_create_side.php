<?php

ini_set('display_errors', '1');
ini_set('memory_limit', '8192M');
set_time_limit(0);

//require '../htdocs/app/Mage.php';
require 'app/Mage.php';
Mage::app();
	const DATA_500 = 500;
	const DATA_700 = 700;

if ($_GET) {
    $delete = $_GET['argument1'];
} else {
    $delete = $argv[1];
}
$category_id = 459;
$products = getProductIdByCategory($category_id);

//Process create new Side
$overlay_image = dirname(__FILE__) . '/700_overlay.png';
$path_media = Mage::getBaseDir('media');
$path_product_image = $path_media . '/catalog/product';
$total_pIds = count($products);
if ($total_pIds > 0) {
    foreach ($products as $product_id) {
	//if ($product_id == 9661) {
	    if ($delete == 'delete') {     //process old data
		deleteOldData($product_id);
		//end
	    }
	    $p = Mage::getModel('catalog/product')->load($product_id);
	    //process get main image
	    $main_image = $p->getImage();

	    //create main image and overlay image
	    $source_image = $path_product_image . $main_image;

	    $img_size = getimagesize($source_image);
	    $width = $img_size[0];
	    if ($width >= DATA_500) {
		$ext = substr($main_image, strrpos($main_image, '.') + 1);
		$str_microtime = str_replace(" ", '', microtime()) . $product_id;
		$filename = 'filename' . $str_microtime . '.' . $ext;
		$filename = validFilename($filename);
		$path_pdp_image = $path_media . '/pdp/images/';
		$desc_img = $path_pdp_image . $filename;
		//create new main image
		copy($source_image, $desc_img);

		//create new overlay image
		$overlayname = 'overlay' . $str_microtime . '.png';
		copy($overlay_image, $path_pdp_image . $overlayname);

		//create object Side
		$model = Mage::getModel('pdp/pdpside');
		$label = $p->getName();
		$data_side = buildDataSide($product_id, $filename, $overlayname, $label);

		$sideId = $model->saveProductSide($data_side);
		if ($width > DATA_700) {
		    //resize image to 700x700
		    resizeImage($desc_img);
		}
	    } else {
		$sideId = 0;
	    }

	    //process get gallery
	    $total = count($p->getMediaGalleryImages());

	    if ($total > 0) {
		$index = 1;
		foreach ($p->getMediaGalleryImages() as $image_p) {
		    $img_ga = $image_p->getFile();
		    $source_image = $path_product_image . $img_ga;
		    $img_size = getimagesize($source_image);
		    $width = $img_size[0];
		    if ($width >= DATA_500) {
			if ($sideId == 0) {
			    //create Side get image from image of gallery
			    $main_image = $img_ga;
			    $ext = substr($main_image, strrpos($main_image, '.') + 1);
			    $str_microtime = str_replace(" ", '', microtime()) . $product_id;
			    $filename = 'filename' . $str_microtime . '.' . $ext;
			    $filename = validFilename($filename);
			    $path_pdp_image = $path_media . '/pdp/images/';

			    $desc_img = $path_pdp_image . $filename;
			    //create new main image
			    copy($source_image, $desc_img);

			    //create new overlay image
			    $overlayname = 'overlay' . $str_microtime . '.png';
			    copy($overlay_image, $path_pdp_image . $overlayname);

			    //create object Side
			    $model = Mage::getModel('pdp/pdpside');
			    $label = $p->getName();
			    $data_side = buildDataSide($product_id, $filename, $overlayname, $label);

			    $sideId = $model->saveProductSide($data_side);
			    if ($width > DATA_700) {
				//resize image to 700x700
				resizeImage($desc_img);
			    }
			}

			if ($main_image != $img_ga) {
			    //process add design color for image of gallery
			    if ($sideId > 0) {
				$productColorInfo = buildDataProductColor($product_id);

				//create color thumb
				$str_microtime = str_replace(" ", '', microtime()) . $product_id . $index;
				$ext = substr($img_ga, strrpos($img_ga, '.') + 1);
				$filename = 'color_thumbnail' . $str_microtime . '.' . $ext;
				$filename = validFilename($filename);
				$path_pdp_image = $path_media . '/pdp/images/color-thumbnail/';
				$desc_img = $path_pdp_image . $filename;
				//create new main image
				copy($source_image, $desc_img);
				if ($width > DATA_700) {
				    //resize image to 700x700
				    resizeImage($desc_img);
				}

				$productColorInfo['color_thumbnail'] = $filename;

				$colorModel = Mage::getModel('pdp/pdpcolor');
				$productColorId = $colorModel->saveProductColor($productColorInfo);
				if ($productColorId) {
				    //Product Color Images
				    $productColorImageInfo['product_color_id'] = $productColorId;
				    $colorImageModel = Mage::getModel('pdp/pdpcolorimage');

				    $productColorImageInfo['side_id'] = $sideId;
				    //create main image and overlay image
				    $source_image = $path_product_image . $img_ga;
				    $ext = substr($img_ga, strrpos($img_ga, '.') + 1);
				    $filename = 'color_image_' . $sideId . $str_microtime . '.' . $ext;
				    $filename = validFilename($filename);

				    $path_pdp_image = $path_media . '/pdp/images/';
				    $desc_img = $path_pdp_image . $filename;

				    //create new main image
				    copy($source_image, $desc_img);

				    //create new overlay image
				    $overlayFilename = 'overlay_image_' . $sideId . $str_microtime . '.png';
				    copy($overlay_image, $path_pdp_image . $overlayFilename);

				    //process product color image
				    if ($filename != "" && $overlayFilename != "") {
					$productColorImageInfo['filename'] = $filename;
					$productColorImageInfo['overlay'] = $overlayFilename;
					$colorImageModel->saveProductColorImage($productColorImageInfo);
				    }
				    if ($width > DATA_700) {
					//resize image to 700x700
					resizeImage($desc_img);
				    }
				}
			    }
			    $index++;
			}
		    }
		}
	    }
	    //set Enable PDC for this product ='Yes'
	    if ($sideId > 0) {
		$data_enable = array(
		    'product_id' => $product_id,
		    'status' => 1
		);
		Mage::getModel('pdp/productstatus')->setProductConfig($data_enable);
	    }
	    //--
	    echo $product_id . "\n";
	//}
    }
    echo '===>Total product:' . $total_pIds;
    exit();
} else {
    echo 'No data';
    exit();
}

function deleteOldData($product_id) {
    //get side of product
    $sides = Mage::getModel('pdp/pdpside')->getDesignSides($product_id);
    foreach ($sides as $side) {
	$old_side_id = $side->getId();
	Mage::getModel('pdp/pdpside')->load($old_side_id)->delete();
    }
    //get product color of product
    $productColors = Mage::getModel('pdp/pdpcolor')->getProductColorCollection($product_id);
    foreach ($productColors as $productColor) {
	$oldProductColorId = $productColor->getId();
	Mage::getModel('pdp/pdpcolor')->deleteProductColor($oldProductColorId);
    }
    //end
}

function resizeImage($desc_img) {
    //resize image to 700x700
    $imageObj = new Varien_Image($desc_img);
    $imageObj->constrainOnly(TRUE);
    $imageObj->keepAspectRatio(TRUE);
    $imageObj->keepFrame(FALSE);
    $imageObj->resize(DATA_700, DATA_700);
    $imageObj->save($desc_img);
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
 * Build data for side
 * @param type $product_id
 * @param type $filename
 * @param type $overlayname
 * @param type $label
 * @return type
 */
function buildDataSide($product_id, $filename, $overlayname, $label) {
    $data['product_id'] = $product_id;
    $data['side_id'] = '';
    $data['current_product_id'] = $product_id;
    $data['area_id'] = '';
    $data['inlay_id'] = '';
    $data['label'] = $label;
    $data['background_type'] = 'image';
    $data['color_code'] = '';
    $data['color_name'] = '';
    $data['inlay_w'] = DATA_700;
    $data['inlay_h'] = DATA_700;
    $data['inlay_t'] = '0';
    $data['inlay_l'] = '0';
    $data['price'] = '';
    $data['position'] = '';
    $data['status'] = 1;
    $data['id'] = '';
    $data['filename'] = $filename;
    $data['overlay'] = $overlayname;
    return $data;
}

/**
 * Build data for product color
 * @param type $product_id
 * @return type
 */
function buildDataProductColor($product_id) {
    return array(
	'product_id' => $product_id,
	'color_code' => 'ffffff',
	'color_name' => '',
	'position' => '',
	'status' => 1
    );
}

/**
 * Validate filename
 * @param type $filename
 * @return type
 */
function validFilename($filename) {
    $validFilename1 = str_replace('_', '', $filename);
    $validFilename2 = str_replace('-', '', $validFilename1);
    return $validFilename2;
}

exit();
?>
