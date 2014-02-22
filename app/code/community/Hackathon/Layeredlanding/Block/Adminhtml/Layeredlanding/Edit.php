<?php
 
class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'layeredlanding';
        $this->_controller = 'adminhtml_layeredlanding';
 
        $this->_updateButton('save', 'label', Mage::helper('layeredlanding')->__('Save Landingpage'));
        $this->_updateButton('delete', 'label', Mage::helper('layeredlanding')->__('Delete Landingpage'));

        $this->_addButton(
            'saveandcontinue',
            array(
                'label' => Mage::helper('layeredlanding')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class' => 'save',
            ),
            -100
        );

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('layeredlanding_data') && Mage::registry('layeredlanding_data')->getId() ) {
            return Mage::helper('layeredlanding')->__("Edit Landingpage '%s'", $this->htmlEscape(Mage::registry('layeredlanding_data')->getPageTitle()));
        } else {
            return Mage::helper('layeredlanding')->__('Add Landingpage');
        }
    }
}