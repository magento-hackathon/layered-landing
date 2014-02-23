<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->addTab('form_section',array(
			'label' => Mage::helper('layeredlanding')->__('Landingpage Content'),
			'title' => Mage::helper('layeredlanding')->__('Landingpage Content'),
			'content' => $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_tab_content')->toHtml(),
        ));

        $this->addTab('conditions_section', array(
            'label' => Mage::helper('layeredlanding')->__('Conditions'),
            'title' => Mage::helper('layeredlanding')->__('Conditions'),
            'content' => $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_tab_conditions')->toHtml(),
        ));

        $this->addTab('design_section', array(
            'label' => Mage::helper('layeredlanding')->__('Custom Design'),
            'title' => Mage::helper('layeredlanding')->__('Custom Design'),
            'content' => $this->getLayout()->createBlock('layeredlanding/adminhtml_layeredlanding_edit_tab_design')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}