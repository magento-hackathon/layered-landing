<?php

class Hackathon_Layeredlanding_Block_Leftnav extends Mage_Core_Block_Template
{
	public function getPagesList()
	{
		$collection = Mage::getModel('layeredlanding/layeredlanding')->getCollection()
			->addFieldToSelect('page_title')
			->addFieldToSelect('page_url');
			
		Mage::getModel('layeredlanding/layeredlanding')->addStoreFilter($collection);
		
		return $collection;
	}
}