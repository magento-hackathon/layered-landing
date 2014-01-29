<?php

class Clusterfuckers_Layeredlanding_IndexController extends Mage_Core_Controller_Front_Action
{
    public function ajaxValuesAction()
    {
		$attribute_id = (int)Mage::app()->getRequest()->getParam('attributeid');
		
		$options = Mage::getResourceModel('eav/entity_attribute_option_collection');
		$options = $options->setAttributeFilter($attribute_id)->setStoreFilter(0)->toOptionArray();
		
		$html = '<option value="">-- select --</option>';
		foreach ($options as $option)
		{
			$html .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
		}
		
		echo $html;
	}
}