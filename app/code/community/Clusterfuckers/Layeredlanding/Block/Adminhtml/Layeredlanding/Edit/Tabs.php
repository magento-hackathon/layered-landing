<?php
 
class Clusterfuckers_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('layeredlanding_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('layeredlanding')->__('Landingpage Information'));
    }
 
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('layeredlanding')->__('Landingpage Information'),
            'title'     => Mage::helper('layeredlanding')->__('Landingpage Information'),
            'content'   => $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_tab_general')->toHtml(),
        ));
       
        return parent::_beforeToHtml();
    }
}