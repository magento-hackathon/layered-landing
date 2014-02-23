<?php

class Hackathon_Layeredlanding_Model_Options_Layout extends Mage_Page_Model_Source_Layout
{

    public function toOptionArray()
    {
        $options = parent::toOptionArray();
		
		array_unshift($options, array('value'=>'', 'label'=>Mage::helper('layeredlanding')->__('Inherit Template')));

        return $options;
    }
}
