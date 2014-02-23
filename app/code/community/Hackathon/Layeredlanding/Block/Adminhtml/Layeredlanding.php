<?php
 
class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_layeredlanding';
        $this->_blockGroup = 'layeredlanding';
        $this->_headerText = Mage::helper('layeredlanding')->__('Landingpage Manager');
        $this->_addButtonLabel = Mage::helper('layeredlanding')->__('Add Landingpage');
        parent::__construct();
    }
}