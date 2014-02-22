<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Renderers_Categoryids extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value = $row->getData($this->getColumn()->getIndex());
		
		$category = Mage::getModel('catalog/category')->load($value);
		return (!is_null($category->getId())) ? $category->getName() : $value ;
	}
}