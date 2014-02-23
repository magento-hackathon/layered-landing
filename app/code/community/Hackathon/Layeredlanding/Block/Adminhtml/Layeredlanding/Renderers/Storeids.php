<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Renderers_Storeids extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$value = explode(",", $row->getData($this->getColumn()->getIndex()));
		
		$label = '';
		foreach ($value as $store)
		{
			if ($store == '0')
			{
				$label .= Mage::helper('layeredlanding')->__('All Stores') . '<br/>';
			}
			else
			{
				$label .= Mage::app()->getStore($store)->getName() . '<br/>';
			}
		}
		
		return $label;
	}
}