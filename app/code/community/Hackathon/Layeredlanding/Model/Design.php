<?php

class Hackathon_Layeredlanding_Model_Design extends Mage_Catalog_Model_Design
{
    public function addLayoutHandleObserver(Varien_Event_Observer $observer)
    {
        $landingpage = Mage::registry('current_landingpage');
		if (is_null($landingpage)) return $this; // no landingpage

		$landingpage_id = $landingpage->getId();

		$layout = $observer->getAction()->getLayout();
		$layout->getUpdate()->addHandle('layeredlanding_page');
		$layout->getUpdate()->addHandle('layeredlanding_' . $landingpage_id);
		$layout->generateXml();

		return $this;
    }

    protected function _extractSettings($object)
    {
        $landingpage = Mage::registry('current_landingpage');

		if (!is_null($landingpage))
		{
			$layout_update = $landingpage->getCustomLayoutUpdate();
			$layout_template = $landingpage->getCustomLayoutTemplate();
			$layout_laynav = (int)$landingpage->getDisplayLayeredNavigation();

			// object is present, not a product page and  landingspage has design updates
			if ($object && !Mage::registry('current_product') && (!empty($layout_update) || !empty($layout_template) || $layout_laynav==0))
			{
				// overwrite the category layout dates
				$object->setCustomDesignDate(array('from'=>null, 'to'=>null));

				// add XML layout update
				$category_design = $object->getCustomLayoutUpdate(); // categories custom layout
				$category_design .= PHP_EOL.$layout_update; // landingpage's custom layout

				// remove the layered navigation block if required
				if ($layout_laynav == 0)
				{
					$category_design .= PHP_EOL."<remove name=\"catalog.leftnav\"/>";
				}

				// set all layout updates
				$object->setCustomLayoutUpdate($category_design);

				// set template if overwritten
				if (!empty($layout_template))
				{
					$object->setPageLayout($layout_template);
				}
			}
		}

		return parent::_extractSettings($object); // process design in parent as usual
    }
}