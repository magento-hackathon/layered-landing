<?php

class Hackathon_Layeredlanding_Block_Canonical extends Mage_Core_Block_Template
{
	public function getCanonicalUrl()
	{
		$landingpage = Mage::registry('current_landingpage');
		
		return ($landingpage && Mage::getStoreConfig('catalog/seo/layeredlanding_canonical_tag')) ? 
			Mage::getBaseUrl() . $landingpage->getPageUrl() : 
			false ;
	}
}