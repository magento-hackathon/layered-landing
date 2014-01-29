<?php
 
class Clusterfuckers_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'layeredlanding';
        $this->_controller = 'adminhtml_layeredlanding';
 
        $this->_updateButton('save', 'label', Mage::helper('layeredlanding')->__('Save Store'));
        $this->_updateButton('delete', 'label', Mage::helper('layeredlanding')->__('Delete Store'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('layeredlanding_data') && Mage::registry('layeredlanding_data')->getId() ) {
            return Mage::helper('layeredlanding')->__("Edit Landingpage '%s'", $this->htmlEscape(Mage::registry('layeredlanding_data')->getTitle()));
        } else {
            return Mage::helper('layeredlanding')->__('Add Landingpage');
        }
    }
}