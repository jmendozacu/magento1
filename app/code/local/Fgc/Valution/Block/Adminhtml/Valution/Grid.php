<?php

class Fgc_Valution_Block_Adminhtml_Valution_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("valutionGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("valution/valution")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("valution")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("your_name", array(
				"header" => Mage::helper("valution")->__("Your name"),
				"index" => "your_name",
				));
				$this->addColumn("your_email", array(
				"header" => Mage::helper("valution")->__("Your email"),
				"index" => "your_email",
				));
				$this->addColumn("phone_number", array(
				"header" => Mage::helper("valution")->__("Phone number"),
				"index" => "phone_number",
				));
						$this->addColumn('country', array(
						'header' => Mage::helper('valution')->__('Country'),
						'index' => 'country',
						'type' => 'options',
						'options'=>Fgc_Valution_Block_Adminhtml_Valution_Grid::getOptionArray3(),				
						));
						
				$this->addColumn("message_titile", array(
				"header" => Mage::helper("valution")->__("Message titile"),
				"index" => "message_titile",
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_valution', array(
					 'label'=> Mage::helper('valution')->__('Remove Valution'),
					 'url'  => $this->getUrl('*/adminhtml_valution/massRemove'),
					 'confirm' => Mage::helper('valution')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray3()
		{
            $data_array=array(); 
			$data_array[0]='United Kingdom';
			$data_array[1]='Afghanistan';
			$data_array[2]='Albania';
			$data_array[3]='Algeria';
			$data_array[4]='American Samoa';
            return($data_array);
		}
		static public function getValueArray3()
		{
            $data_array=array();
			foreach(Fgc_Valution_Block_Adminhtml_Valution_Grid::getOptionArray3() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}