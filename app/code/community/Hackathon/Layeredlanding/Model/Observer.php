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

    public function layeredLandingSaveBefore($observer)
    {
        /** @var $obj Hackathon_Layeredlanding_Model_Layeredlanding */
        $obj = $observer->getDataObject();

        if ($url = $obj->getPageUrl()) {
            $collection = Mage::getModel('core/url_rewrite')
                ->getCollection()
                ->addFieldToFilter('request_path', array('eq' => $url));

            if ($collection->getSize() > 0) {
                throw new Exception("Url already used by product or category");
            }

            $collection = Mage::getModel('cms/page')
                ->getCollection()
                ->addFieldToFilter('identifier', array('eq' => str_replace('.html', '', $url)));

            if ($collection->getSize() > 0) {
                throw new Exception("Url already used by CMS page");
            }
        }
    }

    public function layeredLandingSaveAfter($observer)
    {
        $cache = Mage::app()->getCache();
        $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('TOPMENU'));
    }

    public function pageBlockHtmlTopmenuGethtmlBefore($observer)
    {
        /** @var $menu Varien_Data_Tree_Node */
        $menu = $observer->getMenu();

        $collection = Mage::getModel('layeredlanding/layeredlanding')
            ->getCollection()
            ->addFieldToFilter('display_in_top_navigation', 1);

        $hasActiveEntry = false;

        /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
        foreach ($collection as $landingpage)
        {
            $isActive = Mage::app()->getRequest()->getAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS) == $landingpage->getPageUrl();

            $newNodeData = array(
                'id' => 'layered-landing-'.$landingpage->getId(),
                'name' => $landingpage->getPageTitle(),
                'url' => $landingpage->getUrl(),
                'is_active' => $isActive,
                'is_landingpage' => true
            );

            $newNode = new Varien_Data_Tree_Node($newNodeData, 'test', new Varien_Data_Tree);
            $menu->addChild($newNode);

            $hasActiveEntry = $hasActiveEntry || $isActive;
        }

        if ($hasActiveEntry) {
            foreach ($menu->getChildren() as $child) {
                if (!$child->getIsLandingpage()) {
                    $child->setIsActive(false);
                }
            }
        }
    }

    public function catalogControllerCategoryInitAfter($observer)
    {
        $landingPage = Mage::registry('current_landingpage');
        if ($landingPage) {
            Mage::unregister('current_entity_key');
            Mage::register('current_entity_key', 'landingpage-'.$landingPage->getId());
        }
    }
}