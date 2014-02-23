<?php

class Hackathon_Layeredlanding_Block_Widget_Link extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{
    protected $_serializer = null;

    protected function _construct()
    {
        $this->_serializer = new Varien_Object();
        parent::_construct();
    }

	protected function _toHtml()
	{
		// load page
		$landingpage_id = (int)$this->getData('landingpage');
		$landingpage = Mage::getModel('layeredlanding/layeredlanding')->load($landingpage_id);
		
		// test validity
		if (is_null($landingpage->getId())) return ''; // landingpage does not exist
		
		$stores = explode(',', $landingpage->getData('store_ids'));
		if (!in_array(Mage::app()->getStore()->getId(), $stores)) return ''; // landingpage not present on this store
		
		// create link
		$landingpage_url 	= Mage::getBaseUrl() . DS . $landingpage->getData('page_url');
		$landingpage_name 	= $landingpage->getData('page_title');
		$landingpage_class	= (strlen($this->getData('class'))) ? ' class="'.$this->getData('class').'"' : '' ;
		
		return '<a href="'.$landingpage_url.'"'.$landingpage_class.'>'.$landingpage_name.'</a>';
	}

}