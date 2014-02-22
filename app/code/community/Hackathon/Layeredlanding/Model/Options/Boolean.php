<?php

class Hackathon_Layeredlanding_Model_Options_Boolean extends Mage_Page_Model_Source_Layout
{

    public function toOptionArray()
    {
        $options = array(
			array('value'=>'1', 'label'=>Mage::helper('layeredlanding')->__('Yes')),
			array('value'=>'0', 'label'=>Mage::helper('layeredlanding')->__('No'))
		);

        return $options;
    }
}
