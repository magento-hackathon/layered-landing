<?php
 
class Clusterfuckers_Layeredlanding_Block_Adminhtml_Layeredlanding_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('layeredlandingGrid');
        // This is the primary key of the database
        $this->setDefaultSort('layeredlanding_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('layeredlanding_id', array(
            'header'    => Mage::helper('layeredlanding')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'layeredlanding_id',
        ));
 
        $this->addColumn('title', array(
            'header'    => Mage::helper('layeredlanding')->__('Title'),
            'align'     => 'left',
            'index'     => 'page_title',
        ));
 
        // $this->addColumn('status', array(
 
            // 'header'    => Mage::helper('layeredlanding')->__('Status'),
            // 'align'     => 'left',
            // 'width'     => '80px',
            // 'index'     => 'status',
            // 'type'      => 'options',
            // 'options'   => array(
                // 1 => 'Active',
                // 0 => 'Inactive',
            // ),
        // ));
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}