<?php

class Hackathon_Layeredlanding_Model_Observer extends Mage_Core_Model_Abstract
{
    public function addLayeredRouter($observer)
    {
        $front = $observer->getEvent()->getFront();

        $router = new Hackathon_Layeredlanding_Controller_Router();
        $front->addRouter('hackathon_layeredlanding', $router);
    }

    public function setCategoryData($observer)
    {
        $landingpage = Mage::registry('current_landingpage');
        if (is_null($landingpage)) return $this; // no landingpage available, return

        $category = $observer->getCategory();
        $category->setData('name', $landingpage->getData('page_title'));
        $category->setData('description', $landingpage->getData('page_description'));
    }

    public function coreBlockAbstractToHtmlBefore($observer)
    {
        $landingpage = Mage::registry('current_landingpage');
        // Replace the default template with the layeredlanding one for fixed logic when removing filters
        if ($landingpage && $observer->getBlock() instanceof Mage_Catalog_Block_Layer_State) {
            $observer->getBlock()->setTemplate('layeredlanding/catalog/layer/state.phtml');
        }
    }

    public function coreBlockAbstractPrepareLayoutAfter($observer)
    {
        /** @var $block Mage_Catalog_Block_Category_View */
        $block = $observer->getBlock();

        if ($block instanceof Mage_Catalog_Block_Category_View) {
            /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
            $landingpage = Mage::registry('current_landingpage');

            if ($landingpage) {
                if($landingpage->getImage()) {
                    // Replace category object in registry with an object with the image set
                    $currentCategory = Mage::registry('current_category');
                    $currentCategory->setData('image', '../../' . $landingpage->getImage());
                    Mage::unregister('current_category');
                    Mage::register('current_category', $currentCategory);
                }

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
        else if ($block instanceof Mage_Catalog_Block_Breadcrumbs) {

            /** @var $landingpage Hackathon_Layeredlanding_Model_Layeredlanding */
            $landingpage = Mage::registry('current_landingpage');

            /** @var $breadcrumbsBlock Mage_Page_Block_Html_Breadcrumbs */
            if (($breadcrumbsBlock = $block->getLayout()->getBlock('breadcrumbs')) && $landingpage) {
                $breadcrumbsBlock->addCrumb($landingpage->getPageTitle(), array(
                    'label' => $landingpage->getPageTitle(),
                    'link' => $landingpage->getPageUrl()
                ));
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

    public function addMultipleCategoriesToCollection($observer) {
        // Infinite loop protection
        if (Mage::registry('added_multiple_categories')) return $this;
        Mage::register('added_multiple_categories', true);

        $landingpage = Mage::registry('current_landingpage');
        if(!$landingpage || !(int)$landingpage->getId()) return $this;

        $categoryIdsValue = $landingpage->getCategoryIds();
        $categoryIds = explode(',', $categoryIdsValue);

        // Fetch the extra categories (skip the first because that one is already loaded)
        if (count($categoryIds) > 1) {

            $collection = Mage::getResourceModel('catalog/product_collection');

            // enable filtering on multiple categories
            $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id', null, 'left');
            $collection->addAttributeToFilter('category_id', array('in' => $categoryIds));

            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

            $collection->addAttributeToFilter('visibility', array(
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
            ));
            $collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

            $collection->getSelect()->group('entity_id');
            $collection->addAttributeToSelect(
                Mage::getSingleton('catalog/config')->getProductAttributes()
            );
        }
        return $this;
    }

    public function pageBlockHtmlTopmenuGethtmlBefore($observer)
    {
        /** @var $menu Varien_Data_Tree_Node */
        $menu = $observer->getMenu();

        $collection = Mage::getModel('layeredlanding/layeredlanding')
            ->getCollection()
            ->addFieldToFilter('display_in_top_navigation', 1)
            ->addStoreFilter();

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

            $newNode = new Varien_Data_Tree_Node($newNodeData, 'id', new Varien_Data_Tree);
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