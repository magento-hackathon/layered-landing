<?php

class Hackathon_Layeredlanding_Model_Design extends Mage_Catalog_Model_Design
{
    public function addLayoutHandleObserver(Varien_Event_Observer $observer)
    {
        $landingpage = Mage::registry('current_landingpage');
		if (is_null($landingpage)) return $this; // no landingpage
		
		$landingpage_id = $landingpage->getId();
		// $landingpage_id = 5;
		
		$layout = $observer->getAction()->getLayout();
		
		$layout->getUpdate()->addHandle('layeredlanding_' . $landingpage_id);
		$layout->getUpdate()->addUpdate('
		<layeredlanding_' . $landingpage_id . '>
			<reference name="head">
				<block type="core/text" name="injection_test">
					<action method="setText"><text><![CDATA[foobiedoo]]></text></action>
				</block>
			</reference>
		</layeredlanding_' . $landingpage_id . '>
		');
		
		$layout->generateXml();
		
		// var_dump(Mage::app()->getLayout()->getUpdate()->getHandles());
		// var_dump(Mage::app()->getLayout()->getUpdate()->load('layeredlanding_' . $landingpage_id)->asString());
		// exit;
		
		return $this;
    }
	
    protected function _extractSettings($object)
    {
        $landingpage = Mage::registry('current_landingpage');
		
		if (!is_null($landingpage) && $object && !Mage::registry('current_product')) // there is a landingpage, object is present and this is not a product page
		{
			$category_design = $object->getCustomDesignDate(); // categories custom design
			$category_design .= "\r\n{$landingpage->getCustomLayoutUpdate()}"; 
			
			$object->setCustomDesignDate($category_design);
		}
		
		return parent::_extractSettings($object); // process design in parent as usual
    }
}