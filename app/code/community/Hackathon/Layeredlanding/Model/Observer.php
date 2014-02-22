<?php

class Hackathon_Layeredlanding_Model_Observer extends Mage_Core_Model_Abstract
{
    public function addLayeredRouter($observer)
    {
        $front = $observer->getEvent()->getFront();

        $router = new Hackathon_Layeredlanding_Controller_Router();
        $front->addRouter('hackathon_layeredlanding', $router);
    }

    public function coreBlockAbstractPrepareLayoutAfter($observer)
    {
        /** @var $block Mage_Catalog_Block_Category_View */
        $block = $observer->getBlock();

        if ($block instanceof Mage_Catalog_Block_Category_View) {
            /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
            $landingpage = Mage::registry('current_landingpage');

            if ($landingpage) {
                if ($headBlock = $block->getLayout()->getBlock('head')) {
                    if ($title = $landingpage->getMetaTitle()) {
                        $headBlock->setTitle($title);
                    }
                    if ($description = $landingpage->getMetaDescription()) {
                        $headBlock->setDescription($description);
                    }
                    if ($keywords = $landingpage->getMetaKeywords()) {
                        $headBlock->setKeywords($keywords);
                    }
                }
            }
        }
    }
}