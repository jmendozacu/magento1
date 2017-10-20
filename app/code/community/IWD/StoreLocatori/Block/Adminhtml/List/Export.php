<?php
class IWD_StoreLocatori_Block_Adminhtml_List_Export extends Mage_Adminhtml_Block_Widget_Grid{
	
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'grid' );
		$this->setDefaultSort ( 'id' );
		$this->setDefaultDir ( 'desc' );
		$this->setSaveParametersInSession ( true );
	}
	
	protected function _prepareCollection(){
		$collection = Mage::getModel('storelocatori/stores')->getCollection();
		
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	
	protected function _prepareColumns() {
		$this->addColumn ( 'title',
				array (
						'header' => Mage::helper ( 'storelocatori' )->__ ( 'title' ),
						'align' => 'left', 
						'index' => 'title'
				)
		);
		
		$this->addColumn ( 'is_active',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'is_active' ),
		        'align' => 'left',
		        'index' => 'is_active'
		    )
		);
		
		$this->addColumn ( 'phone',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'phone' ),
		        'align' => 'left',
		        'index' => 'phone',
		        'filter'=>false
		    )
		);
		
		
		
		$this->addColumn ( 'country_id',
				array (
						'header' => Mage::helper ( 'storelocatori' )->__ ( 'country_id' ),
						'align' => 'left', 
						'index' => 'country_id',						
						'filter'=>false
				)
		);
		
		
		$this->addColumn ( 'region',
				array (
						'header' => Mage::helper ( 'storelocatori' )->__ ( 'region' ),
						'align' => 'left', 
						'index' => 'region',						
						'filter'=>false,
				        'renderer'  =>  'IWD_StoreLocatori_Block_Adminhtml_List_Render_Export_Region',
				)
		);
		
		
		$this->addColumn ( 'street',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'street' ),
		        'align' => 'left',
		        'index' => 'street',
		        'filter'=>false
		    )
		);
		
		
		 
		
		$this->addColumn ( 'city',
				array (
						'header' => Mage::helper ( 'storelocatori' )->__ ( 'city' ),
						'align' => 'left',
						'index' => 'city'
				)
		);
		
		
		$this->addColumn ( 'postal_code',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'postal_code' ),
		        'align' => 'left',
		        'index' => 'postal_code',
		        'filter'=>false
		    )
		);
		
		$this->addColumn ( 'desc',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'desc' ),
		        'align' => 'left',
		        'index' => 'desc',
		        'filter'=>false
		    )
		);
		
		
		$this->addColumn ( 'latitude',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'latitude' ),
		        'align' => 'left',
		        'index' => 'latitude'
		    )
		);
		
		$this->addColumn ( 'longitude',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'longitude' ),
		        'align' => 'left',
		        'index' => 'longitude'
		    )
		);
		
		
		$this->addColumn ( 'stores',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'stores' ),
		        'align' => 'left',
		        'index' => 'store_id',
		        'renderer'  =>  'IWD_StoreLocatori_Block_Adminhtml_List_Render_Export_Stores',
		    )
		);
		
		$this->addColumn ( 'website',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'website' ),
		        'align' => 'left',
		        'index' => 'website'
		    )
		);
		
		$this->addColumn ( 'icon',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'icon' ),
		        'align' => 'left',
		        'index' => 'icon'
		    )
		);
		
		$this->addColumn ( 'image',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'image' ),
		        'align' => 'left',
		        'index' => 'image'
		    )
		);
		
		$this->addColumn ( 'position',
		    array (
		        'header' => Mage::helper ( 'storelocatori' )->__ ( 'position' ),
		        'align' => 'left',
		        'index' => 'position'
		    )
		);
		

		return parent::_prepareColumns ();
	}
	
	/**
	 * Row click url
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('store_id' => $row->getId()));
	}
	
	protected function _afterLoadCollection()
	{
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}
	
	protected function _filterStoreCondition($collection, $column)
	{
		if (!$value = $column->getFilter()->getValue()) {
			return;
		}
	
		$this->getCollection()->addStoreFilter($value);
	}
}