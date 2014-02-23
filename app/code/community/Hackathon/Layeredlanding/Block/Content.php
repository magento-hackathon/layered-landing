<?php

class Hackathon_Layeredlanding_Block_Content extends Mage_Core_Block_Template
{
	public function __construct()
	{
		$this->setData('title', 'foob');
		$this->setData('description', 'hello world');
		
		$category = Mage::registry('current_category');
		Mage::unregister('current_category');
		
		$category->setData('name', 'foob');
		Mage::register('current_category', $category);
/*		
		$layer = Mage::getSingleton('catalog/layer')->getState()->getFilters();
		foreach ($layer as $attribute)
		{
			var_dump($attribute->getFilter()->getAttributeModel()->getSource()->getAllOptions(false));exit;
			echo $attribute->getName(); // Name of the filter
			echo $attribute->getLabel(); // Currently selected value
			echo $attribute->getFilter()->getRequestVar(); // Filter code (usually attribute code, except category filter, where it equals "cat")
		}
*/
	}
}