<?php
 
class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
 
        $this->addColumn('category_ids', array(
            'header'    => Mage::helper('layeredlanding')->__('Categories'),
            'align'     => 'left',
            'index'     => 'category_ids',
            'width'     => '100px',
        ));
 
        $this->addColumn('store_ids', array(
            'header'    => Mage::helper('layeredlanding')->__('Stores'),
            'align'     => 'left',
            'index'     => 'store_ids',
            'width'     => '100px',
        ));
 
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