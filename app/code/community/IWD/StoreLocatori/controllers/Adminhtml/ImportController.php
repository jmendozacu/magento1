<?php
class IWD_StoreLocatori_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action{
	
	
	protected function _initAction(){
		// load layout, set active menu and breadcrumbs
		$this->loadLayout()
			->_setActiveMenu('storelocatori/list')
			->_addBreadcrumb(Mage::helper('storelocatori')->__('Store Locator'), Mage::helper('storelocatori')->__('Store Locator'))
			->_addBreadcrumb(Mage::helper('storelocatori')->__('Import Stores'), Mage::helper('storelocatori')->__('Import Stores'));
		return $this;
	}
	
	
	public function indexAction(){
		
		$maxUploadSize = Mage::helper('importexport')->getMaxUploadSize();
		
		$this->_getSession()->addNotice(
				$this->__('Total size of uploadable files must not exceed %s', $maxUploadSize)
		);
		
		$this->_getSession()->addNotice(
				$this->__('<a href="http://demo.iwdextensions.com/store-locator/import_example.csv">Example of import file</a>')
		);
		
		
		
		$this->_initAction()
				->_title($this->__('Store Locator'))->_title($this->__('Import Stores'))
				->_addBreadcrumb($this->__('Import'), $this->__('Import'));
		
		$this->renderLayout();
	}
	
	
	/**
	 * Validate uploaded files action.
	 *
	 * @return void
	 */
	public function validateAction()
	{
		$data = $this->getRequest()->getPost();
		if ($data) {
			$data['entity'] = 'storelocatori';
			$this->loadLayout(false);
			/** @var $resultBlock Mage_ImportExport_Block_Adminhtml_Import_Frame_Result */
			$resultBlock = $this->getLayout()->getBlock('import.frame.result.storelocatori');
			// common actions
			$resultBlock->addAction('show', 'import_validation_container')
			->addAction('clear', array(
					Mage_ImportExport_Model_Import::FIELD_NAME_SOURCE_FILE,
					Mage_ImportExport_Model_Import::FIELD_NAME_IMG_ARCHIVE_FILE)
			);
	
			try {
				/** @var $import Mage_ImportExport_Model_Import */
				$import = Mage::getModel('storelocatori/import');
				$sourceFile = $import->setData($data)->uploadSource();
				$result = $import->importSource($sourceFile, $resultBlock);
				if (!$result['error']){
					$resultBlock->addSuccess(
							$this->__('Import has been completed.')
					);
				}else{
					$resultBlock->addNotice($this->__('Please fix errors and re-upload file'));
				}
				
	
				
			} catch (Exception $e) {
				$resultBlock->addNotice($this->__('Please fix errors and re-upload file'))->addError($e->getMessage());
			}
			$this->renderLayout();
		} elseif ($this->getRequest()->isPost() && empty($_FILES)) {
			$this->loadLayout(false);
			$resultBlock = $this->getLayout()->getBlock('import.frame.result');
			$resultBlock->addError($this->__('File was not uploaded'));
			$this->renderLayout();
		} else {
			$this->_getSession()->addError($this->__('Data is invalid or file is not uploaded'));
			$this->_redirect('*/*/index');
		}
	}

	
	public function fillAction(){
		Mage::register('import_storelocatori', true);
		$_helper = Mage::helper('storelocatori/geolocation')->fillGeoData();
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('storelocatori')->__('Geo Data have been updated successfully.'));
		$this->_redirect('*/adminhtml_list/index');
		
	}
	
	
	public function removeallAction(){
		$collection = Mage::getModel('storelocatori/stores')->getCollection();
		foreach ($collection as $item){
			try{
				$item->delete();
			}catch(Exception $e){
				Mage::logException($e);
			}
		}
		
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('storelocatori')->__('Stores have been remove successfully.'));
		$this->_redirect('*/adminhtml_list/index');
		
	}
	
}