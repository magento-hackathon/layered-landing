<?php
 
class Hackathon_Layeredlanding_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function attributeIdToCode($attribute_id)
	{
		return Mage::getModel('eav/entity_attribute')->load((int)$attribute_id)->getAttributeCode();
	}
}