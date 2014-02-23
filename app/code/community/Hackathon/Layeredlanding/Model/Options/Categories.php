<?php

class Hackathon_Layeredlanding_Model_Options_Categories extends Mage_Core_Model_Abstract
{

    public function toOptionArray()
    {
		$categoriesArray = Mage::getModel('catalog/category')
			->getCollection()
			->addAttributeToSelect('name')
			->addAttributeToSelect('level')
			->addAttributeToSort('path', 'asc')
			->addFieldToFilter('is_active', array('eq'=>'1'))
			->addFieldToFilter('level', array('gt'=>'1'))
			->load()
			->toArray();
	 
		foreach ($categoriesArray as $categoryId => $category) 
		{
			if (!isset($category['name'])) continue;
			$categories[] = array('value' => $categoryId,'label' => str_repeat('.', $category['level']) . ' ' . $category['name']);
		}
	 
		return $categories;
    }
	
	
}