<?php
 
class Hackathon_Layeredlanding_Model_Attributes extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layeredlanding/attributes');
    }
	
	public function getGridOptionsHtml($attribute_id = 0, $store_id = 0, $option_id = 0, $input_name)
	{
		$attribute = Mage::getModel('eav/entity_attribute')->load((int)$attribute_id);
		
        if ($attribute->getId() && in_array($attribute->getData('frontend_input'), array('select','multiselect')))
		{
            $options = Mage::getResourceModel('eav/entity_attribute_option_collection');
            $options = $options->setAttributeFilter($attribute_id)->setStoreFilter($store_id)->toOptionArray();
			
            $html = '<select name="'.$input_name.'" class="input-select attribute-value" onchange="_estimate_product_count();">';
            $html .= '<option value="">-- select --</option>';

            foreach ($options as $option)
            {
				$selected = ((int)$option['value'] == (int)$option_id) ? 'selected ' : '' ;
                $html .= '<option '.$selected.'value="' . $option['value'] . '">' . $option['label'] . '</option>';
            }
			
            return $html . '</select>';
        }
		else
		{
			return '<input type="text" name="'.$input_name.'" onchange="_estimate_product_count();" class="input-text required-input attribute-value" value="'.$option_id.'"/>';
		}
	}
}