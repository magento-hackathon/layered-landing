<?php

class Hackathon_Layeredlanding_Model_Options_Landingpages
{
    public function toOptionArray()
    {
		$collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection()
			->addFieldToSelect('layeredlanding_id')
			->addFieldToSelect('store_ids')
			->addFieldToSelect('page_title');
		
		$options = array();
		foreach ($collection as $item)
		{
			$stores = array();
			foreach (explode(',', $item->getData('store_ids')) as $store)
			{
				$stores[] = Mage::app()->getStore($store)->getName();
			}
			
			$options[] = array('value' => $item->getData('layeredlanding_id'), 'label' => $item->getData('page_title').' ('.implode(', ', $stores).')');
		}
		
		return $options;
    }
}
