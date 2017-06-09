<?php

class Hackathon_Layeredlanding_Model_Options_Layout extends Mage_Page_Model_Source_Layout
{

    public function toOptionArray($withEmpty = false)
    {
        $options = parent::toOptionArray($withEmpty);
		
		array_unshift($options, array('value'=>'', 'label'=>Mage::helper('layeredlanding')->__('Inherit Template')));

        return $options;
    }
}
