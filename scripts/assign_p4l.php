<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('memory_limit', '1024M'); 

set_time_limit(0);

$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');

umask(0);

Mage:: app("default") -> setCurrentStore(1);
																
								Mage::app()->setCurrentStore(0);
								$category_x = new Mage_Catalog_Model_Category();
								$category_x->load('989'); 
								$products = $category_x->getProductCollection();
								$i=0;
								foreach($products as $product){
									$i++;			
									$ar = $product->getCategoryIds();		
									array_push($ar,"430","46");																							
									
									print_r($ar)."\n";
											
									$pr = Mage::getModel("catalog/product")->load($product->getId());
									$pr->setCategoryIds($ar);
									$pr->save();
									
									
									
									
									
									
													
									
								}
								
								
								
             
     				
								                            
	                    

			

