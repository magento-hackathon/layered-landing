<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Renderer_Attributes
 extends Mage_Adminhtml_Block_Widget
 implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Initialize block
     */
    public function __construct()
    {
        $this->setTemplate('layeredlanding/attributes.phtml');
    }

    /**
     * Render HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
	
	/**
     * Get select options for attributes dropdown
     *
     * @param int $active_id
     * @return string
	 */ 
	public function getAttributeOptions($active_id = 0)
	{
		$attributes = Mage::getModel('layeredlanding/options_attributes')->toOptionArray();
		
		$html = '<option value="">-- select --</option>';
		foreach ($attributes as $attribute)
		{
			$selected = ((int)$attribute['value'] == $active_id) ? 'selected ' : '' ;
			
			$html .= '<option ' . $selected . 'value="' . $attribute['value'] . '">' . $attribute['label'] . '</option>';
		}
		
		return $html;
	}
	
	/**
     * Get select options for values dropdown
     *
     * @param int $attribute_id
     * @param int $option_id
     * @return string
	 */ 
	public function getValueOptions($attribute_id = 0, $option_id = 0)
	{
		$store_ids = Mage::registry('layeredlanding_data')->getStoreIds();
		if (explode(',', $store_ids) > 1) // if more than 1 store just use system level
		{
			$store_ids = 0;
		}
		
		return Mage::getModel('layeredlanding/attributes')->($attribute_id, $store_ids, $option_id);
	}
}