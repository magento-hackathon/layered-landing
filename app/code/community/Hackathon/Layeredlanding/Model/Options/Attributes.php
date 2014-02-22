<?php
 
class Hackathon_Layeredlanding_Model_Options_Attributes extends Mage_Core_Model_Abstract
{

    public function toOptionArray()
	{
		$collection = Mage::getResourceModel('catalog/product_attribute_collection')
						->addFieldToFilter('is_filterable', array('gt' => 0))
						->getItems();
		
		$attributes = array();
		foreach ($collection as $item)
		{
			$attributes[] = array(
				'value' => $item->getData('attribute_id'),
				'label' => $item->getData('frontend_label')
			);
		}
		
		return $attributes;
	}
}