<?php

class Hackathon_Layeredlanding_Model_Layout extends Mage_Core_Model_Abstract
{
    public function addLayoutHandle(Varien_Event_Observer $observer)
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
}