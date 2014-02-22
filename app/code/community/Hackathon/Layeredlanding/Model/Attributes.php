<?php
 
class Hackathon_Layeredlanding_Model_Attributes extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('layeredlanding/attributes');
    }
	
	public function getGridOptionsHtml($attribute_id = 0, $store_id = 0, $option_id = 0)
	{
		$attribute = Mage::getModel('eav/entity_attribute')->load((int)$attribute_id);
		
        if ($attribute->getId() && $attribute->getData('frontend_input') == 'select') 
		{
            $options = Mage::getResourceModel('eav/entity_attribute_option_collection');
            $options = $options->setAttributeFilter($attribute_id)->setStoreFilter($store_id)->toOptionArray();

            $html = '<option value="">-- select --</option>';
            foreach ($options as $option)
            {
				$selected = ((int)$option['value'] == (int)$option_id) ? 'selected ' : '' ;
                $html .= '<option '.$selected.'value="' . $option['value'] . '">' . $option['label'] . '</option>';
            }
			
            return $html;
        }
		else
		{
			return '';
		}
	}
}